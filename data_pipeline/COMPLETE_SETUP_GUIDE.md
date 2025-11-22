# Complete Setup Guide - Từ Đầu Đến Cuối

**Hướng dẫn setup hoàn chỉnh LensArt Data Pipeline từ A-Z**

---

## 📋 Prerequisites

- Docker Desktop installed and running
- 8GB+ RAM available
- Ports available: 2181, 9092, 8080, 8081, 5432, 5050, 8000

---

## 🚀 BƯỚC 1: Start Docker Services

### 1.1. Navigate to docker directory
```bash
cd data_pipeline/docker
```

### 1.2. Start all services
```bash
docker-compose up -d
```

**Expected output:**
```
Creating network "lensart-network" ... done
Creating zookeeper ... done
Creating postgres ... done
Creating kafka ... done
Creating kafka-ui ... done
Creating pgadmin ... done
Creating flink-jobmanager ... done
Creating flink-taskmanager ... done
```

### 1.3. Verify services are running
```bash
docker-compose ps
```

**Expected: All services should be "Up"**

### 1.4. Wait for services to be ready (~60 seconds)
```bash
# Check Kafka logs
docker logs kafka --tail 20

# Should see: "started (kafka.server.KafkaServer)"
```

---

## 🗄️ BƯỚC 2: Verify PostgreSQL Database

### 2.1. Connect to PostgreSQL
```bash
docker exec -it postgres psql -U postgres -d lensart_events
```

### 2.2. List tables (should see 5 tables)
```sql
\dt
```

**Expected output:**
```
               List of relations
 Schema |          Name          | Type  |  Owner   
--------+------------------------+-------+----------
 public | order_items_analytics  | table | postgres
 public | order_metrics          | table | postgres
 public | order_status_history   | table | postgres
 public | orders_processed       | table | postgres
 public | orders_raw             | table | postgres
```

### 2.3. Exit PostgreSQL
```sql
\q
```

---

## 📨 BƯỚC 3: Create Kafka Topics

### 3.1. Create all topics
```bash
# Topic 1: order-created
docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-created \
  --bootstrap-server localhost:9092 \
  --partitions 3 \
  --replication-factor 1 \
  --if-not-exists

# Topic 2: order-updated
docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-updated \
  --bootstrap-server localhost:9092 \
  --partitions 3 \
  --replication-factor 1 \
  --if-not-exists

# Topic 3: order-cancelled
docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-cancelled \
  --bootstrap-server localhost:9092 \
  --partitions 3 \
  --replication-factor 1 \
  --if-not-exists

# Topic 4: order-events
docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-events \
  --bootstrap-server localhost:9092 \
  --partitions 3 \
  --replication-factor 1 \
  --if-not-exists

# Topic 5: order-events-dlq (Dead Letter Queue)
docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-events-dlq \
  --bootstrap-server localhost:9092 \
  --partitions 1 \
  --replication-factor 1 \
  --if-not-exists
```

**Or create all at once:**
```bash
for topic in order-created order-updated order-cancelled order-events order-events-dlq; do
  docker exec kafka /usr/bin/kafka-topics --create \
    --topic $topic \
    --bootstrap-server localhost:9092 \
    --partitions 3 \
    --replication-factor 1 \
    --if-not-exists
done
```

### 3.2. Verify topics created
```bash
docker exec kafka /usr/bin/kafka-topics --list --bootstrap-server localhost:9092
```

**Expected output:**
```
order-cancelled
order-created
order-events
order-events-dlq
order-updated
```

### 3.3. Describe a topic (see details)
```bash
docker exec kafka /usr/bin/kafka-topics --describe \
  --bootstrap-server localhost:9092 \
  --topic order-created
```

---

## 🌐 BƯỚC 4: Configure Laravel

### 4.1. Update .env file
```bash
# Go back to Laravel root
cd ../..

# Add Kafka configuration to .env
echo "" >> .env
echo "# Kafka Configuration" >> .env
echo "KAFKA_BROKERS=localhost:9092" >> .env
echo "KAFKA_ORDER_TOPIC=order-events" >> .env
echo "KAFKA_ORDER_CREATED_TOPIC=order-created" >> .env
echo "KAFKA_ORDER_UPDATED_TOPIC=order-updated" >> .env
echo "KAFKA_ORDER_CANCELLED_TOPIC=order-cancelled" >> .env
echo "KAFKA_PRODUCER_TIMEOUT=10000" >> .env
echo "KAFKA_PRODUCER_ASYNC=true" >> .env
echo "KAFKA_REQUIRED_ACK=1" >> .env
```

### 4.2. Clear Laravel cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 4.3. Start Laravel server
```bash
php artisan serve
```

**Laravel should be running at: http://localhost:8000**

---

## 🧪 BƯỚC 5: Test End-to-End

### 5.1. Test Kafka Connection (no auth required)
```bash
# Open new terminal
curl http://localhost:8000/api/kafka/test-connection
```

**Expected success response:**
```json
{
    "status": "success",
    "message": "Kafka connection test successful",
    "kafka_brokers": "localhost:9092"
}
```

### 5.2. Send test event to Kafka
```bash
curl -X POST http://localhost:8000/api/kafka/test-connection
```

**Note**: For protected endpoints, you need authentication token.

---

## 🔍 BƯỚC 6: Verify Data Flow

### 6.1. Check Kafka UI
Open browser: **http://localhost:8080**

Steps:
1. Click **"Topics"** (left sidebar)
2. Click **"order-events"** or **"order-created"**
3. Click **"Messages"** tab
4. You should see test events sent from Laravel

### 6.2. Consume messages from Kafka (command line)
```bash
# Read messages from order-events topic
docker exec kafka /usr/bin/kafka-console-consumer \
  --bootstrap-server localhost:9092 \
  --topic order-events \
  --from-beginning

# Press Ctrl+C to stop
```

### 6.3. Check Flink Dashboard
Open browser: **http://localhost:8081**

**Note**: No jobs running yet (will deploy in Phase 2)

### 6.4. Check PgAdmin
Open browser: **http://localhost:5050**

Login:
- Email: `admin@lensart.com`
- Password: `admin`

Add server:
- Host: `postgres`
- Port: `5432`
- Username: `postgres`
- Password: `postgres`
- Database: `lensart_events`

---

## 📊 BƯỚC 7: Send Real Order Events

### 7.1. Create sample order (via Laravel API)
```bash
# You need valid auth token
curl -X POST http://localhost:8000/api/orders/create \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "branch_id": 1,
    "address": "123 Test Street, Ho Chi Minh",
    "note": "Test order",
    "order_details": [
      {
        "product_id": 1,
        "color": "Black",
        "quantity": 1,
        "total_price": 500000
      }
    ]
  }'
```

### 7.2. Send order-created event
```bash
curl -X POST http://localhost:8000/api/kafka/events/order-created \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"order_id": 1}'
```

### 7.3. Verify event in Kafka
```bash
docker exec kafka /usr/bin/kafka-console-consumer \
  --bootstrap-server localhost:9092 \
  --topic order-created \
  --from-beginning \
  --max-messages 1
```

---

## 🛠️ Useful Commands

### Check Service Status
```bash
cd data_pipeline/docker
docker-compose ps
```

### View Service Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker logs kafka --tail 50 -f
docker logs postgres --tail 50 -f
docker logs flink-jobmanager --tail 50 -f
```

### Stop All Services
```bash
cd data_pipeline/docker
docker-compose down
```

### Restart All Services
```bash
cd data_pipeline/docker
docker-compose restart
```

### Remove All Data (⚠️ DESTRUCTIVE!)
```bash
cd data_pipeline/docker
docker-compose down -v
```

---

## 🐛 Troubleshooting

### Issue: Kafka topics not created
```bash
# Check Kafka is ready
docker logs kafka --tail 20

# Should see "started (kafka.server.KafkaServer)"

# If not, wait 30-60 seconds and try again
```

### Issue: Port already in use
```bash
# Check what's using port 9092
netstat -ano | findstr "9092"  # Windows
lsof -i :9092                   # Mac/Linux

# Kill the process or change port in docker-compose.yml
```

### Issue: PostgreSQL connection refused
```bash
# Check PostgreSQL is ready
docker exec postgres pg_isready -U postgres

# Should output: accepting connections
```

### Issue: Laravel cannot connect to Kafka
```bash
# Check .env has correct broker address
cat .env | grep KAFKA

# Clear cache
php artisan config:clear
```

---

## 📈 What's Next: Phase 2

### Deploy Flink Jobs (Coming Soon)
To process events from Kafka → PostgreSQL:

1. **OrderEventProcessor** - Process all order events
2. **OrderStatusTracker** - Track status changes
3. **RealTimeMetricsAggregator** - Calculate real-time metrics

**Status**: Infrastructure ready ✅  
**Next**: Develop Flink Jobs (Java/Maven)

---

## ✅ Success Checklist

- [ ] Docker services running (7 containers)
- [ ] Kafka topics created (5 topics)
- [ ] PostgreSQL tables initialized (5 tables)
- [ ] Laravel connected to Kafka
- [ ] Test event sent successfully
- [ ] Event visible in Kafka UI
- [ ] All web UIs accessible:
  - [ ] Kafka UI: http://localhost:8080
  - [ ] Flink: http://localhost:8081
  - [ ] PgAdmin: http://localhost:5050
  - [ ] Laravel: http://localhost:8000

---

## 📞 Support

If you encounter issues:
1. Check logs: `docker-compose logs -f [service]`
2. Verify .env configuration
3. Ensure all ports are available
4. Check Docker has enough resources (8GB+ RAM)

---

**Last Updated**: 2024-11-18  
**Version**: 1.0.0

