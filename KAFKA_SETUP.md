# Kafka Integration Setup

## ✅ Đã hoàn thành

Kafka đã được tích hợp vào OrderService. Mỗi khi order được tạo thành công, event sẽ tự động được publish vào Kafka.

## ⚙️ Configuration

Thêm vào file `.env`:

```env
# Kafka Configuration
KAFKA_ENABLED=true                    # Enable Kafka publishing
KAFKA_BROKERS=localhost:9092          # Kafka broker address
KAFKA_USE_DOCKER=true                 # Use docker exec to publish (recommended)
```

## 🔄 How It Works

### 1. Order Created Flow:

```
User đặt hàng
    ↓
OrderService->store()
    ↓
1. Validate & Save order ✅
2. KafkaService->publishOrderCreated() 📢
    ↓
Docker exec kafka-console-producer
    ↓
Kafka Topic: order-created
    ↓
Flink Job consumes & processes
    ↓
PostgreSQL (sales_transactions table)
```

### 2. Event Format:

```json
{
  "event_type": "order.created",
  "order_id": 123,
  "user_id": 456,
  "branch_id": 1,
  "total_price": 750000,
  "order_status": "Đang xử lý",
  "payment_status": "Chưa thanh toán",
  "payment_method": "COD",
  "coupon_id": null,
  "date": "2024-11-25 10:30:00",
  "order_details": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 250000,
      "total_price": 500000,
      "color": "Black"
    },
    {
      "product_id": 2,
      "quantity": 1,
      "price": 250000,
      "total_price": 250000,
      "color": "Blue"
    }
  ],
  "timestamp": "2024-11-25T10:30:00+07:00"
}
```

## 🧪 Testing

### 1. Start Kafka (if not running):

```bash
cd lensart_pipeline\docker
docker-compose up -d
```

### 2. Enable Kafka in Laravel:

Update `.env`:
```env
KAFKA_ENABLED=true
```

### 3. Create a test order:

```bash
POST /api/orders
Authorization: Bearer {token}

{
  "branch_id": 1,
  "payment_method": "COD",
  "order_details": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 250000,
      "total_price": 500000,
      "color": "Black"
    }
  ]
}
```

### 4. Check Laravel logs:

```bash
tail -f storage/logs/laravel.log | grep KAFKA
```

Expected:
```
[KAFKA] Message published via Docker
topic: order-created
message_size: 456
```

### 5. Verify in Kafka UI:

```
Open http://localhost:8080
→ Topics → order-created
→ Messages
→ You should see the event!
```

### 6. Check PostgreSQL:

```bash
docker exec postgres psql -U postgres -d lensart_events -c "SELECT * FROM sales_transactions ORDER BY created_at DESC LIMIT 5;"
```

## 🔧 Implementation Details

### KafkaService Methods:

1. **publishOrderCreated($order)**
   - Builds order event with order_details array
   - Calls `publish()` method

2. **publish($topic, $message)**
   - Routes to appropriate publishing method
   - Handles enabled/disabled state

3. **publishViaDocker($topic, $message)**
   - Uses `docker exec kafka-console-producer`
   - Works when Kafka is in Docker container
   - Default method (KAFKA_USE_DOCKER=true)

4. **publishViaSocket($topic, $message)**
   - Direct socket connection to Kafka
   - For when Kafka is NOT in Docker
   - TODO: Full implementation

## ⚠️ Important Notes

### Error Handling:
- ✅ Kafka publish failures do NOT fail order creation
- ✅ Errors are logged for monitoring
- ✅ Order is always saved successfully

### Performance:
- Publishing is synchronous (~100-200ms)
- Does not block order creation significantly
- Can be made async if needed

### Docker Requirements:
- Kafka container must be named `kafka`
- Container must be running
- Docker must be accessible from PHP

## 🐛 Troubleshooting

### Issue: "docker command not found"

**Solution:** Make sure Docker is in PATH
```bash
# Windows: Add Docker to PATH
# Or restart terminal after Docker Desktop installation
```

### Issue: "kafka container not found"

**Solution:** Start Kafka
```bash
cd lensart_pipeline\docker
docker-compose up -d kafka
```

### Issue: Events not appearing in Kafka

**Check:**
```bash
# 1. Check Kafka is running
docker ps | grep kafka

# 2. Check topic exists
docker exec kafka /usr/bin/kafka-topics --list --bootstrap-server localhost:9092

# 3. Create topic if missing
docker exec kafka /usr/bin/kafka-topics --create --topic order-created --bootstrap-server localhost:9092 --partitions 3 --replication-factor 1
```

### Issue: "shell_exec disabled"

**Solution:** Enable shell_exec in php.ini
```ini
; Remove shell_exec from disable_functions
disable_functions = 
```

## 🚀 Production Deployment

### For Azure deployment:

**Option 1: Ngrok Tunnel (Development)**
```bash
ngrok tcp 9092
# Update .env with ngrok URL
```

**Option 2: Azure Event Hubs (Production)**
```env
KAFKA_ENABLED=true
KAFKA_BROKERS=your-namespace.servicebus.windows.net:9093
KAFKA_USE_DOCKER=false
# Implement socket publishing for Event Hubs
```

## 📊 Monitoring

### View Laravel logs:
```bash
tail -f storage/logs/laravel.log | grep KAFKA
```

### View Kafka messages:
```bash
docker exec kafka /usr/bin/kafka-console-consumer --bootstrap-server localhost:9092 --topic order-created --from-beginning
```

### View Flink processing:
```bash
# Open Flink Dashboard
open http://localhost:8081
```

### View results in PostgreSQL:
```bash
docker exec postgres psql -U postgres -d lensart_events -c "SELECT COUNT(*) FROM sales_transactions;"
```

---

**Status:** ✅ Ready to use  
**Version:** 1.0.0  
**Date:** 25/11/2024
