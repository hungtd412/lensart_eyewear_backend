# Sales Transactions API - Đơn Giản Hóa

**Format:** Chỉ gửi dữ liệu giao dịch bán hàng vào Kafka  
**Nguyên tắc:** 1 sản phẩm = 1 event riêng  
**Fields:** Chỉ 6 trường cần thiết

---

## 📊 Event Format

### Transaction Event Structure

```json
{
  "order_id": 123,
  "product_id": 456,
  "quantity": 2,
  "price": 500000.00,
  "timestamp": "2024-11-22T10:30:00+07:00",
  "customer_id": 789
}
```

**Không có field dư thừa!** Chỉ 6 fields này.

---

## 🚀 API Endpoint

### POST `/api/kafka/transactions/sales`

Gửi tất cả sản phẩm trong 1 order vào Kafka (mỗi product = 1 event riêng).

#### Request

**URL:**
```
POST http://localhost:8000/api/kafka/transactions/sales
```

**Headers:**
```
Authorization: Bearer YOUR_ACCESS_TOKEN
Content-Type: application/json
```

**Body:**
```json
{
  "order_id": 123
}
```

#### Response (Success)

```json
{
  "status": "success",
  "message": "Sales transactions sent to Kafka successfully",
  "order_id": 123,
  "results": {
    "success": 3,
    "failed": 0,
    "total": 3
  },
  "format": {
    "order_id": "integer",
    "product_id": "integer",
    "quantity": "integer",
    "price": "decimal",
    "timestamp": "ISO8601 string",
    "customer_id": "integer"
  }
}
```

**Giải thích:** Order có 3 sản phẩm → gửi 3 events riêng biệt vào Kafka.

#### Response (Error)

```json
{
  "status": "error",
  "message": "Order not found"
}
```

---

## 💡 Ví Dụ Thực Tế

### Scenario: Order có 3 sản phẩm

**Order #123 bao gồm:**
- Product #1: Kính mát Ray-Ban (qty: 1, price: 1,500,000 VND)
- Product #2: Gọng kính Gucci (qty: 2, price: 2,000,000 VND)
- Product #3: Tròng kính chống ánh sáng xanh (qty: 1, price: 500,000 VND)

**Customer:** User ID 789

---

### Request

```bash
curl -X POST http://localhost:8000/api/kafka/transactions/sales \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..." \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": 123
  }'
```

---

### Kafka sẽ nhận 3 events riêng biệt:

#### Event 1: Kính mát Ray-Ban
```json
{
  "order_id": 123,
  "product_id": 1,
  "quantity": 1,
  "price": 1500000.00,
  "timestamp": "2024-11-22T10:30:00+07:00",
  "customer_id": 789
}
```

#### Event 2: Gọng kính Gucci
```json
{
  "order_id": 123,
  "product_id": 2,
  "quantity": 2,
  "price": 2000000.00,
  "timestamp": "2024-11-22T10:30:00+07:00",
  "customer_id": 789
}
```

#### Event 3: Tròng kính
```json
{
  "order_id": 123,
  "product_id": 3,
  "quantity": 1,
  "price": 500000.00,
  "timestamp": "2024-11-22T10:30:00+07:00",
  "customer_id": 789
}
```

---

## 🔧 Kafka Topic

**Topic Name:** `order-created` (hoặc có thể đổi thành `sales-transactions`)

**Partitions:** 3  
**Replication Factor:** 1  
**Retention:** 7 days

---

## 📊 Database Schema (Simplified)

### Table: sales_transactions

Nếu muốn store transactions từ Kafka vào PostgreSQL (via Flink):

```sql
CREATE TABLE sales_transactions (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    timestamp TIMESTAMP NOT NULL,
    customer_id INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Indexes
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id),
    INDEX idx_customer_id (customer_id),
    INDEX idx_timestamp (timestamp)
);
```

---

## 🎯 Flink Job (Simplified)

### Job: SalesTransactionProcessor

**Input:** Kafka topic `order-created` hoặc `sales-transactions`

**Processing:**
```
1. Deserialize JSON → SalesTransaction object
2. Validate fields (không null, price > 0, quantity > 0)
3. Write to PostgreSQL: sales_transactions table
```

**Code skeleton (Java):**

```java
public class SalesTransactionProcessor {
    public static void main(String[] args) throws Exception {
        StreamExecutionEnvironment env = 
            StreamExecutionEnvironment.getExecutionEnvironment();
        
        // Kafka Source
        KafkaSource<String> kafkaSource = KafkaSource.<String>builder()
            .setBootstrapServers("kafka:29092")
            .setTopics("order-created")
            .setGroupId("sales-transaction-processor")
            .setValueOnlyDeserializer(new SimpleStringSchema())
            .build();
        
        // Process stream
        DataStream<SalesTransaction> transactions = env
            .fromSource(kafkaSource, WatermarkStrategy.noWatermarks(), "Kafka")
            .map(json -> parseSalesTransaction(json))
            .filter(t -> t != null && isValid(t));
        
        // Sink to PostgreSQL
        transactions.addSink(new JdbcSink<>(
            "INSERT INTO sales_transactions " +
            "(order_id, product_id, quantity, price, timestamp, customer_id) " +
            "VALUES (?, ?, ?, ?, ?, ?)",
            (ps, t) -> {
                ps.setInt(1, t.orderId);
                ps.setInt(2, t.productId);
                ps.setInt(3, t.quantity);
                ps.setBigDecimal(4, t.price);
                ps.setTimestamp(5, t.timestamp);
                ps.setInt(6, t.customerId);
            },
            jdbcConnectionOptions
        ));
        
        env.execute("Sales Transaction Processor");
    }
    
    private static SalesTransaction parseSalesTransaction(String json) {
        // Parse JSON to object
    }
    
    private static boolean isValid(SalesTransaction t) {
        return t.quantity > 0 && t.price.compareTo(BigDecimal.ZERO) > 0;
    }
}
```

---

## 🧪 Testing

### 1. Test với cURL

```bash
# Test endpoint
curl -X POST http://localhost:8000/api/kafka/transactions/sales \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"order_id": 1}'
```

### 2. Verify trong Kafka UI

1. Mở http://localhost:8080
2. Click **Topics** → **order-created**
3. Click **Messages** tab
4. Xem events vừa gửi

### 3. Consume từ Kafka (command line)

```bash
docker exec kafka /usr/bin/kafka-console-consumer \
  --bootstrap-server localhost:9092 \
  --topic order-created \
  --from-beginning \
  --max-messages 10
```

### 4. Verify trong PostgreSQL (sau khi có Flink job)

```bash
docker exec -it postgres psql -U postgres -d lensart_events

# Query transactions
SELECT * FROM sales_transactions 
WHERE order_id = 123 
ORDER BY product_id;
```

---

## 📝 Use Cases

### 1. Real-time Analytics
- Tính tổng doanh thu theo sản phẩm
- Top 10 sản phẩm bán chạy
- Doanh thu theo giờ/ngày/tháng

### 2. Inventory Management
- Track số lượng bán ra
- Alert khi stock thấp
- Forecast demand

### 3. Customer Analytics
- Sản phẩm phổ biến theo customer segment
- Purchase patterns
- Recommendation engine data

### 4. Business Intelligence
- Dashboard real-time
- Sales reports
- Trend analysis

---

## ⚙️ Configuration

### Kafka Producer Config (`config/kafka.php`)

```php
'topics' => [
    'order_created' => env('KAFKA_ORDER_CREATED_TOPIC', 'order-created'),
    // hoặc riêng:
    'sales_transactions' => env('KAFKA_SALES_TRANSACTIONS_TOPIC', 'sales-transactions'),
],
```

### Environment Variables (`.env`)

```env
KAFKA_BROKERS=localhost:9092
KAFKA_ORDER_CREATED_TOPIC=order-created
# hoặc riêng:
KAFKA_SALES_TRANSACTIONS_TOPIC=sales-transactions
```

---

## 🔍 Troubleshooting

### Issue 1: Order không có sản phẩm

```json
{
  "status": "success",
  "results": {
    "success": 0,
    "failed": 0,
    "total": 0
  }
}
```

**Giải pháp:** Check order_details table có data không.

---

### Issue 2: Một số events failed

```json
{
  "status": "error",
  "results": {
    "success": 2,
    "failed": 1,
    "total": 3
  }
}
```

**Giải pháp:** 
- Check Kafka logs: `docker logs kafka --tail 50`
- Check Laravel logs: `storage/logs/laravel.log`
- Verify Kafka connection

---

### Issue 3: Events không xuất hiện trong Kafka

**Check:**
1. Kafka service running: `docker ps | grep kafka`
2. Topic exists: `docker exec kafka kafka-topics.sh --list --bootstrap-server localhost:9092`
3. Laravel logs: `tail -f storage/logs/laravel.log`

---

## 🚀 Deployment Checklist

### Development
- [x] API endpoint implemented
- [x] KafkaService method created
- [x] Route registered
- [ ] Test với Postman/cURL
- [ ] Verify events in Kafka UI

### Production
- [ ] Authentication enabled
- [ ] Rate limiting configured
- [ ] Error monitoring (Sentry, etc.)
- [ ] Kafka cluster setup
- [ ] Flink job deployed
- [ ] Database replicated
- [ ] Backup strategy

---

## 📊 Performance Considerations

### Throughput
- **Async sending:** KafkaService gửi async
- **Batch processing:** Flink xử lý batch
- **Expected load:** 1000 transactions/minute

### Scaling
- **Kafka partitions:** 3 (có thể tăng)
- **Flink parallelism:** 4 task slots
- **PostgreSQL:** Connection pooling

### Monitoring
- Kafka lag monitoring
- Flink checkpoint success rate
- Database write throughput

---

## 📚 References

- [Kafka Documentation](https://kafka.apache.org/documentation/)
- [Flink Kafka Connector](https://nightlies.apache.org/flink/flink-docs-release-1.18/docs/connectors/datastream/kafka/)
- [Laravel Kafka Integration](../KAFKA_SETUP.md)

---

## ✨ Summary

**What changed:**
- ✅ Simplified event format (chỉ 6 fields)
- ✅ 1 product = 1 event (không group)
- ✅ Clean data structure (no nested objects)
- ✅ Easy to process in Flink

**Benefits:**
- 🚀 Simpler to process
- 📊 Easy to aggregate
- 🔍 Better for analytics
- ⚡ Better performance

---

**Version:** 1.0.0  
**Last Updated:** 22/11/2024  
**Status:** ✅ Ready to use

