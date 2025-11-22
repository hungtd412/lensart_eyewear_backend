package com.lensart.pipeline;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.lensart.pipeline.models.SalesTransaction;
import com.lensart.pipeline.models.ProductSales;
import org.apache.flink.api.common.eventtime.WatermarkStrategy;
import org.apache.flink.api.common.functions.AggregateFunction;
import org.apache.flink.api.common.serialization.SimpleStringSchema;
import org.apache.flink.connector.jdbc.JdbcConnectionOptions;
import org.apache.flink.connector.jdbc.JdbcExecutionOptions;
import org.apache.flink.connector.jdbc.JdbcSink;
import org.apache.flink.connector.kafka.source.KafkaSource;
import org.apache.flink.connector.kafka.source.enumerator.initializer.OffsetsInitializer;
import org.apache.flink.streaming.api.datastream.DataStream;
import org.apache.flink.streaming.api.environment.StreamExecutionEnvironment;
import org.apache.flink.streaming.api.windowing.assigners.TumblingProcessingTimeWindows;
import org.apache.flink.streaming.api.windowing.time.Time;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.math.BigDecimal;
import java.sql.Timestamp;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

/**
 * Product Sales Aggregator Job
 * 
 * Aggregates sales by product_id:
 * - Total quantity sold
 * - Total revenue
 * 
 * Updates product_sales table via UPSERT
 */
public class ProductSalesAggregatorJob {
    
    private static final Logger LOG = LoggerFactory.getLogger(ProductSalesAggregatorJob.class);
    private static final ObjectMapper objectMapper = new ObjectMapper();
    
    // Configuration
    private static final String KAFKA_BOOTSTRAP_SERVERS = getEnvOrDefault("KAFKA_BOOTSTRAP_SERVERS", "kafka:29092");
    private static final String KAFKA_TOPIC = getEnvOrDefault("KAFKA_TOPIC", "order-created");
    private static final String KAFKA_GROUP_ID = getEnvOrDefault("KAFKA_GROUP_ID", "product-sales-aggregator");
    
    private static final String POSTGRES_URL = getEnvOrDefault("POSTGRES_URL", "jdbc:postgresql://postgres:5432/lensart_events");
    private static final String POSTGRES_USER = getEnvOrDefault("POSTGRES_USER", "postgres");
    private static final String POSTGRES_PASSWORD = getEnvOrDefault("POSTGRES_PASSWORD", "postgres");
    
    private static final int CHECKPOINT_INTERVAL = Integer.parseInt(getEnvOrDefault("CHECKPOINT_INTERVAL", "60000"));
    private static final int WINDOW_SIZE_SECONDS = Integer.parseInt(getEnvOrDefault("WINDOW_SIZE_SECONDS", "2"));

    public static void main(String[] args) throws Exception {
        LOG.info("========================================");
        LOG.info("Starting Product Sales Aggregator Job");
        LOG.info("========================================");
        LOG.info("Kafka Bootstrap Servers: {}", KAFKA_BOOTSTRAP_SERVERS);
        LOG.info("Kafka Topic: {}", KAFKA_TOPIC);
        LOG.info("Window Size: {} minutes", WINDOW_SIZE_SECONDS);
        LOG.info("========================================");

        // Setup Flink environment
        final StreamExecutionEnvironment env = StreamExecutionEnvironment.getExecutionEnvironment();
        env.enableCheckpointing(CHECKPOINT_INTERVAL);

        // Configure Kafka Source
        KafkaSource<String> kafkaSource = KafkaSource.<String>builder()
                .setBootstrapServers(KAFKA_BOOTSTRAP_SERVERS)
                .setTopics(KAFKA_TOPIC)
                .setGroupId(KAFKA_GROUP_ID)
                .setStartingOffsets(OffsetsInitializer.earliest())
                .setValueOnlyDeserializer(new SimpleStringSchema())
                .build();

        // Read from Kafka
        DataStream<String> kafkaStream = env.fromSource(
                kafkaSource,
                WatermarkStrategy.noWatermarks(),
                "Kafka Source - Product Sales"
        );

        // Parse to SalesTransaction
        DataStream<SalesTransaction> transactions = kafkaStream
                .map(json -> parseSalesTransaction(json))
                .filter(t -> t != null && t.isValid())
                .name("Parse Transactions");

        // Aggregate by product_id with time window
        DataStream<ProductSales> productSales = transactions
                .keyBy(t -> t.productId)
                .window(TumblingProcessingTimeWindows.of(Time.seconds(WINDOW_SIZE_SECONDS)))
                .aggregate(new ProductSalesAggregator())
                .name("Aggregate by Product");

        // Write to PostgreSQL using UPSERT function
        productSales.addSink(
                JdbcSink.sink(
                        "SELECT upsert_product_sales(?, ?, ?, ?)",
                        
                        (ps, sales) -> {
                            ps.setInt(1, sales.productId);
                            ps.setInt(2, sales.totalQuantity);
                            ps.setBigDecimal(3, sales.totalRevenue);
                            ps.setTimestamp(4, Timestamp.valueOf(sales.lastSaleDate));
                        },
                        
                        JdbcExecutionOptions.builder()
                                .withBatchSize(50)
                                .withBatchIntervalMs(5000)
                                .withMaxRetries(3)
                                .build(),
                        
                        new JdbcConnectionOptions.JdbcConnectionOptionsBuilder()
                                .withUrl(POSTGRES_URL)
                                .withDriverName("org.postgresql.Driver")
                                .withUsername(POSTGRES_USER)
                                .withPassword(POSTGRES_PASSWORD)
                                .build()
                )
        ).name("PostgreSQL Sink - product_sales");

        // Execute job
        env.execute("Product Sales Aggregator");
        LOG.info("Product Sales Aggregator Job completed!");
    }

    /**
     * Parse JSON to SalesTransaction
     */
    private static SalesTransaction parseSalesTransaction(String json) {
        try {
            JsonNode node = objectMapper.readTree(json);
            
            int orderId = node.get("order_id").asInt();
            int productId = node.get("product_id").asInt();
            int quantity = node.get("quantity").asInt();
            
            BigDecimal price;
            if (node.get("price").isTextual()) {
                price = new BigDecimal(node.get("price").asText());
            } else {
                price = BigDecimal.valueOf(node.get("price").asDouble());
            }
            
            String timestampStr = node.get("timestamp").asText();
            LocalDateTime timestamp = parseTimestamp(timestampStr);
            
            int customerId = node.get("customer_id").asInt();
            
            return new SalesTransaction(orderId, productId, quantity, price, timestamp, customerId);
        } catch (Exception e) {
            LOG.error("Failed to parse transaction: {}", json, e);
            return null;
        }
    }

    /**
     * Parse timestamp
     */
    private static LocalDateTime parseTimestamp(String timestampStr) {
        try {
            return LocalDateTime.parse(timestampStr, DateTimeFormatter.ISO_DATE_TIME);
        } catch (Exception e1) {
            try {
                return LocalDateTime.parse(timestampStr, DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss"));
            } catch (Exception e2) {
                try {
                    return LocalDateTime.parse(timestampStr, DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm"));
                } catch (Exception e3) {
                    return LocalDateTime.parse(timestampStr + " 00:00:00", 
                                               DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss"));
                }
            }
        }
    }

    /**
     * Get environment variable or default
     */
    private static String getEnvOrDefault(String key, String defaultValue) {
        String value = System.getenv(key);
        return (value != null && !value.isEmpty()) ? value : defaultValue;
    }

    /**
     * Aggregator for product sales
     */
    public static class ProductSalesAggregator implements AggregateFunction<SalesTransaction, ProductSalesAccumulator, ProductSales> {
        
        @Override
        public ProductSalesAccumulator createAccumulator() {
            return new ProductSalesAccumulator();
        }

        @Override
        public ProductSalesAccumulator add(SalesTransaction transaction, ProductSalesAccumulator acc) {
            acc.productId = transaction.productId;
            acc.totalQuantity += transaction.quantity;
            acc.totalRevenue = acc.totalRevenue.add(transaction.price);
            
            // Track latest sale date
            if (acc.lastSaleDate == null || transaction.timestamp.isAfter(acc.lastSaleDate)) {
                acc.lastSaleDate = transaction.timestamp;
            }
            
            return acc;
        }

        @Override
        public ProductSales getResult(ProductSalesAccumulator acc) {
            ProductSales sales = new ProductSales();
            sales.productId = acc.productId;
            sales.totalQuantity = acc.totalQuantity;
            sales.totalRevenue = acc.totalRevenue;
            sales.lastSaleDate = acc.lastSaleDate != null ? acc.lastSaleDate : LocalDateTime.now();
            return sales;
        }

        @Override
        public ProductSalesAccumulator merge(ProductSalesAccumulator a, ProductSalesAccumulator b) {
            a.totalQuantity += b.totalQuantity;
            a.totalRevenue = a.totalRevenue.add(b.totalRevenue);
            
            if (b.lastSaleDate != null && 
                (a.lastSaleDate == null || b.lastSaleDate.isAfter(a.lastSaleDate))) {
                a.lastSaleDate = b.lastSaleDate;
            }
            
            return a;
        }
    }

    /**
     * Accumulator for product sales
     */
    public static class ProductSalesAccumulator {
        public Integer productId;
        public int totalQuantity = 0;
        public BigDecimal totalRevenue = BigDecimal.ZERO;
        public LocalDateTime lastSaleDate;
    }
}

