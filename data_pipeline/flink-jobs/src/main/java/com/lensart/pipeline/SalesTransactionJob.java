package com.lensart.pipeline;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;
import com.lensart.pipeline.models.SalesTransaction;
import org.apache.flink.api.common.eventtime.WatermarkStrategy;
import org.apache.flink.api.common.serialization.SimpleStringSchema;
import org.apache.flink.connector.jdbc.JdbcConnectionOptions;
import org.apache.flink.connector.jdbc.JdbcExecutionOptions;
import org.apache.flink.connector.jdbc.JdbcSink;
import org.apache.flink.connector.jdbc.JdbcStatementBuilder;
import org.apache.flink.connector.kafka.source.KafkaSource;
import org.apache.flink.connector.kafka.source.enumerator.initializer.OffsetsInitializer;
import org.apache.flink.streaming.api.datastream.DataStream;
import org.apache.flink.streaming.api.environment.StreamExecutionEnvironment;
import org.apache.flink.streaming.api.functions.sink.SinkFunction;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.math.BigDecimal;
import java.sql.Timestamp;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

/**
 * LensArt Sales Transaction Processing Job
 * 
 * Reads sales transaction events from Kafka and writes to PostgreSQL
 * Format: order_id, product_id, quantity, price, timestamp, customer_id
 */
public class SalesTransactionJob {
    
    private static final Logger LOG = LoggerFactory.getLogger(SalesTransactionJob.class);
    private static final ObjectMapper objectMapper = new ObjectMapper();
    
    // Configuration
    private static final String KAFKA_BOOTSTRAP_SERVERS = getEnvOrDefault("KAFKA_BOOTSTRAP_SERVERS", "kafka:29092");
    private static final String KAFKA_TOPIC = getEnvOrDefault("KAFKA_TOPIC", "order-created");
    private static final String KAFKA_GROUP_ID = getEnvOrDefault("KAFKA_GROUP_ID", "sales-transaction-processor");
    
    private static final String POSTGRES_URL = getEnvOrDefault("POSTGRES_URL", "jdbc:postgresql://postgres:5432/lensart_events");
    private static final String POSTGRES_USER = getEnvOrDefault("POSTGRES_USER", "postgres");
    private static final String POSTGRES_PASSWORD = getEnvOrDefault("POSTGRES_PASSWORD", "postgres");
    
    private static final int CHECKPOINT_INTERVAL = Integer.parseInt(getEnvOrDefault("CHECKPOINT_INTERVAL", "60000"));
    private static final int JDBC_BATCH_SIZE = Integer.parseInt(getEnvOrDefault("JDBC_BATCH_SIZE", "100"));
    private static final int JDBC_BATCH_INTERVAL_MS = Integer.parseInt(getEnvOrDefault("JDBC_BATCH_INTERVAL_MS", "1000"));

    public static void main(String[] args) throws Exception {
        LOG.info("========================================");
        LOG.info("Starting LensArt Sales Transaction Job");
        LOG.info("========================================");
        LOG.info("Kafka Bootstrap Servers: {}", KAFKA_BOOTSTRAP_SERVERS);
        LOG.info("Kafka Topic: {}", KAFKA_TOPIC);
        LOG.info("Kafka Group ID: {}", KAFKA_GROUP_ID);
        LOG.info("PostgreSQL URL: {}", POSTGRES_URL);
        LOG.info("Checkpoint Interval: {}ms", CHECKPOINT_INTERVAL);
        LOG.info("JDBC Batch Size: {}", JDBC_BATCH_SIZE);
        LOG.info("========================================");

        // 1. Setup Flink execution environment
        final StreamExecutionEnvironment env = StreamExecutionEnvironment.getExecutionEnvironment();
        
        // Enable checkpointing for fault tolerance (exactly-once semantics)
        env.enableCheckpointing(CHECKPOINT_INTERVAL);
        
        // Set parallelism (can be overridden at deployment)
        // env.setParallelism(2);

        // 2. Configure Kafka Source
        KafkaSource<String> kafkaSource = KafkaSource.<String>builder()
                .setBootstrapServers(KAFKA_BOOTSTRAP_SERVERS)
                .setTopics(KAFKA_TOPIC)
                .setGroupId(KAFKA_GROUP_ID)
                .setStartingOffsets(OffsetsInitializer.earliest()) // Start from earliest on first run
                .setValueOnlyDeserializer(new SimpleStringSchema())
                .build();

        LOG.info("Kafka source configured successfully");

        // 3. Read from Kafka
        DataStream<String> kafkaStream = env.fromSource(
                kafkaSource,
                WatermarkStrategy.noWatermarks(),
                "Kafka Source - " + KAFKA_TOPIC
        );

        // 4. Parse JSON to SalesTransaction objects
        DataStream<SalesTransaction> transactions = kafkaStream
                .map(json -> {
                    try {
                        return parseSalesTransaction(json);
                    } catch (Exception e) {
                        LOG.error("Failed to parse transaction: {}", json, e);
                        return null;
                    }
                })
                .filter(transaction -> {
                    if (transaction == null) {
                        return false;
                    }
                    if (!transaction.isValid()) {
                        LOG.warn("Invalid transaction filtered out: {}", transaction);
                        return false;
                    }
                    return true;
                })
                .name("Parse and Validate Transactions");

        LOG.info("Transaction parsing configured");

        // 5. Write to PostgreSQL
        SinkFunction<SalesTransaction> jdbcSink = JdbcSink.sink(
                "INSERT INTO sales_transactions " +
                "(order_id, product_id, quantity, price, timestamp, customer_id) " +
                "VALUES (?, ?, ?, ?, ?, ?) " +
                "ON CONFLICT DO NOTHING", // Avoid duplicates if reprocessing
                
                (JdbcStatementBuilder<SalesTransaction>) (preparedStatement, transaction) -> {
                    preparedStatement.setInt(1, transaction.orderId);
                    preparedStatement.setInt(2, transaction.productId);
                    preparedStatement.setInt(3, transaction.quantity);
                    preparedStatement.setBigDecimal(4, transaction.price);
                    preparedStatement.setTimestamp(5, Timestamp.valueOf(transaction.timestamp));
                    preparedStatement.setInt(6, transaction.customerId);
                },
                
                JdbcExecutionOptions.builder()
                        .withBatchSize(JDBC_BATCH_SIZE)
                        .withBatchIntervalMs(JDBC_BATCH_INTERVAL_MS)
                        .withMaxRetries(3)
                        .build(),
                
                new JdbcConnectionOptions.JdbcConnectionOptionsBuilder()
                        .withUrl(POSTGRES_URL)
                        .withDriverName("org.postgresql.Driver")
                        .withUsername(POSTGRES_USER)
                        .withPassword(POSTGRES_PASSWORD)
                        .build()
        );

        transactions.addSink(jdbcSink).name("PostgreSQL Sink - sales_transactions");

        LOG.info("PostgreSQL sink configured successfully");

        // 6. Execute the job
        env.execute("LensArt Sales Transaction Processor");
        
        LOG.info("Job execution completed!");
    }

    /**
     * Parse JSON string to SalesTransaction object
     * 
     * Expected format:
     * {
     *   "order_id": 123,
     *   "product_id": 456,
     *   "quantity": 2,
     *   "price": 500000.00,
     *   "timestamp": "2024-11-22 10:30:00",
     *   "customer_id": 789
     * }
     */
    private static SalesTransaction parseSalesTransaction(String json) throws Exception {
        JsonNode node = objectMapper.readTree(json);
        
        // Extract fields
        int orderId = node.get("order_id").asInt();
        int productId = node.get("product_id").asInt();
        int quantity = node.get("quantity").asInt();
        
        // Parse price
        BigDecimal price;
        if (node.get("price").isTextual()) {
            price = new BigDecimal(node.get("price").asText());
        } else {
            price = BigDecimal.valueOf(node.get("price").asDouble());
        }
        
        // Parse timestamp (handle both ISO8601 and SQL datetime formats)
        String timestampStr = node.get("timestamp").asText();
        LocalDateTime timestamp = parseTimestamp(timestampStr);
        
        int customerId = node.get("customer_id").asInt();
        
        SalesTransaction transaction = new SalesTransaction(
            orderId, productId, quantity, price, timestamp, customerId
        );
        
        LOG.debug("Parsed transaction: {}", transaction);
        
        return transaction;
    }

    /**
     * Parse timestamp from multiple formats
     */
    private static LocalDateTime parseTimestamp(String timestampStr) {
        try {
            // Try ISO8601 format first
            return LocalDateTime.parse(timestampStr, DateTimeFormatter.ISO_DATE_TIME);
        } catch (Exception e1) {
            try {
                // Try SQL datetime format: "yyyy-MM-dd HH:mm:ss"
                return LocalDateTime.parse(timestampStr, DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss"));
            } catch (Exception e2) {
                try {
                    // Try without seconds
                    return LocalDateTime.parse(timestampStr, DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm"));
                } catch (Exception e3) {
                    // Try date only
                    return LocalDateTime.parse(timestampStr + " 00:00:00", 
                                               DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss"));
                }
            }
        }
    }

    /**
     * Get environment variable or default value
     */
    private static String getEnvOrDefault(String key, String defaultValue) {
        String value = System.getenv(key);
        return (value != null && !value.isEmpty()) ? value : defaultValue;
    }
}

