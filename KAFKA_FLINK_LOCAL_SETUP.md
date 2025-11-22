# Local Development Setup - Kafka + Flink + PostgreSQL với Docker

## 📋 Tổng quan Kiến trúc

```
┌─────────────────┐
│  Laravel API    │
│  (Port 8000)    │
└────────┬────────┘
         │ HTTP POST (Order Events)
         ↓
┌─────────────────┐
│  Kafka Broker   │
│  (Port 9092)    │
│  Topics:        │
│  - order-events │
│  - order-created│
│  - order-updated│
└────────┬────────┘
         │ Stream Events
         ↓
┌─────────────────┐
│  Apache Flink   │
│  (Port 8081)    │
│  - Job Manager  │
│  - Task Manager │
│  Process & ETL  │
└────────┬────────┘
         │ Write Processed Data
         ↓
┌─────────────────┐
│  PostgreSQL     │
│  (Port 5432)    │
│  Database:      │
│  - order_events │
└─────────────────┘
```

## 🎯 Mục tiêu

1. **Laravel API** gửi order events qua HTTP API
2. **Kafka** nhận và lưu trữ events trong topics
3. **Flink** đọc events từ Kafka, xử lý/transform data
4. **PostgreSQL** lưu trữ data đã được xử lý

## 🐳 Docker Services

### 1. Apache Kafka + Zookeeper
- **Zookeeper**: Quản lý cluster Kafka (port 2181)
- **Kafka Broker**: Message broker (port 9092)
- **Kafka UI**: Web interface để monitor (port 8080)

### 2. Apache Flink
- **Job Manager**: Điều phối jobs (port 8081 - Web UI)
- **Task Manager**: Thực thi tasks

### 3. PostgreSQL
- **Database**: Lưu trữ processed events (port 5432)
- **PgAdmin**: Web UI quản lý database (port 5050)

## 📊 Data Flow Chi tiết

### Flow 1: Order Created
```
1. User tạo đơn hàng → Laravel API
2. Laravel gửi event → POST /api/kafka/events/order-created
3. KafkaService push event → Kafka topic "order-created"
4. Flink Job consume event từ Kafka
5. Flink transform data:
   - Validate order data
   - Enrich với thông tin bổ sung
   - Calculate metrics (total orders, revenue)
6. Flink sink data → PostgreSQL tables:
   - orders_raw: Raw event data
   - orders_processed: Processed order info
   - order_metrics: Aggregated metrics
```

### Flow 2: Order Status Changed
```
1. Admin thay đổi trạng thái đơn hàng
2. Laravel gửi event → POST /api/kafka/events/order-status-changed
3. Event → Kafka topic "order-events"
4. Flink Job consume và process:
   - Track status history
   - Calculate processing time
   - Update metrics
5. Write to PostgreSQL:
   - order_status_history
   - order_metrics (update)
```

### Flow 3: Real-time Analytics
```
1. Flink continuously processes events
2. Calculate real-time metrics:
   - Orders per minute/hour
   - Revenue by branch
   - Top products
   - Customer behavior
3. Store in PostgreSQL analytics tables
4. (Optional) Expose via API for dashboard
```

## 🗄️ Database Schema (PostgreSQL)

### Table: orders_raw
```sql
CREATE TABLE orders_raw (
    id SERIAL PRIMARY KEY,
    event_id VARCHAR(100) UNIQUE NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    order_id INTEGER NOT NULL,
    user_id INTEGER,
    branch_id INTEGER,
    total_price DECIMAL(15, 2),
    order_status VARCHAR(50),
    payment_status VARCHAR(50),
    payment_method VARCHAR(50),
    event_data JSONB,
    event_timestamp TIMESTAMP,
    processed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Table: orders_processed
```sql
CREATE TABLE orders_processed (
    id SERIAL PRIMARY KEY,
    order_id INTEGER UNIQUE NOT NULL,
    user_id INTEGER,
    user_name VARCHAR(255),
    user_email VARCHAR(255),
    branch_id INTEGER,
    branch_name VARCHAR(255),
    order_date TIMESTAMP,
    total_price DECIMAL(15, 2),
    order_status VARCHAR(50),
    payment_status VARCHAR(50),
    payment_method VARCHAR(50),
    items_count INTEGER,
    created_at TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Table: order_status_history
```sql
CREATE TABLE order_status_history (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50),
    changed_at TIMESTAMP,
    processing_time_seconds INTEGER,
    event_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Table: order_metrics
```sql
CREATE TABLE order_metrics (
    id SERIAL PRIMARY KEY,
    metric_date DATE NOT NULL,
    metric_hour INTEGER,
    branch_id INTEGER,
    total_orders INTEGER DEFAULT 0,
    total_revenue DECIMAL(15, 2) DEFAULT 0,
    avg_order_value DECIMAL(15, 2),
    pending_orders INTEGER DEFAULT 0,
    completed_orders INTEGER DEFAULT 0,
    cancelled_orders INTEGER DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(metric_date, metric_hour, branch_id)
);
```

### Table: order_items_analytics
```sql
CREATE TABLE order_items_analytics (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    product_name VARCHAR(255),
    color VARCHAR(50),
    quantity INTEGER,
    unit_price DECIMAL(15, 2),
    total_price DECIMAL(15, 2),
    order_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🔧 Flink Jobs Dự kiến

### Job 1: OrderEventProcessor
**Mục đích**: Xử lý tất cả order events và lưu vào database

**Input**: Kafka topics (order-created, order-updated, order-cancelled)
**Processing**:
- Deserialize JSON events
- Validate data
- Enrich với metadata
- Transform to database model

**Output**: 
- PostgreSQL: orders_raw, orders_processed

### Job 2: OrderStatusTracker
**Mục đích**: Theo dõi lịch sử thay đổi trạng thái

**Input**: Kafka topic (order-events) - filter event_type = "order.status_changed"
**Processing**:
- Track status changes
- Calculate processing time between statuses
- Detect anomalies (e.g., stuck orders)

**Output**: 
- PostgreSQL: order_status_history

### Job 3: RealTimeMetricsAggregator
**Mục đích**: Tính toán metrics real-time

**Input**: All order events
**Processing**:
- Window aggregation (tumbling window per hour)
- Calculate:
  - Total orders
  - Total revenue
  - Average order value
  - Orders by status
  - Orders by branch

**Output**: 
- PostgreSQL: order_metrics

### Job 4: ProductAnalytics (Optional)
**Mục đích**: Phân tích sản phẩm được order

**Input**: order-created events
**Processing**:
- Extract order items
- Count product popularity
- Calculate revenue by product

**Output**: 
- PostgreSQL: order_items_analytics

## 📁 Cấu trúc Project

```
lensart_eyewear_backend/
│
├── app/                                    # Laravel Application
│   ├── Http/
│   │   └── Controllers/
│   │       └── KafkaEventController.php    # Kafka event API
│   ├── Services/
│   │   └── KafkaService.php                # Kafka producer service
│   ├── Events/
│   │   └── OrderEvent.php
│   └── ...
│
├── config/
│   └── kafka.php                           # Laravel Kafka config
│
├── routes/
│   └── kafka.api.php                       # Kafka API routes
│
├── data_pipeline/                          # 🎯 Data Processing Pipeline
│   │
│   ├── docker/                             # Docker Infrastructure
│   │   ├── docker-compose.yml              # All services definition
│   │   ├── .env.docker                     # Docker environment variables
│   │   │
│   │   ├── kafka/
│   │   │   └── kafka-topics.sh             # Script tạo Kafka topics
│   │   │
│   │   ├── postgres/
│   │   │   └── init.sql                    # Database schema initialization
│   │   │
│   │   └── flink/
│   │       └── flink-conf.yaml             # Flink configuration (optional)
│   │
│   ├── flink-jobs/                         # Flink Jobs Source Code
│   │   ├── pom.xml                         # Maven configuration
│   │   ├── README.md                       # Flink jobs documentation
│   │   │
│   │   └── src/
│   │       └── main/
│   │           ├── java/
│   │           │   └── com/lensart/pipeline/
│   │           │       │
│   │           │       ├── jobs/
│   │           │       │   ├── OrderEventProcessor.java
│   │           │       │   ├── OrderStatusTracker.java
│   │           │       │   └── RealTimeMetricsAggregator.java
│   │           │       │
│   │           │       ├── models/
│   │           │       │   ├── OrderEvent.java
│   │           │       │   ├── OrderMetrics.java
│   │           │       │   └── OrderStatusHistory.java
│   │           │       │
│   │           │       ├── serializers/
│   │           │       │   └── OrderEventDeserializer.java
│   │           │       │
│   │           │       ├── sinks/
│   │           │       │   └── PostgresSink.java
│   │           │       │
│   │           │       └── utils/
│   │           │           ├── KafkaConfig.java
│   │           │           └── DatabaseConfig.java
│   │           │
│   │           └── resources/
│   │               ├── application.properties
│   │               └── log4j2.properties
│   │
│   ├── scripts/                            # Helper Scripts
│   │   ├── start-all.sh                    # Start all Docker services
│   │   ├── stop-all.sh                     # Stop all services
│   │   ├── restart-all.sh                  # Restart services
│   │   ├── deploy-jobs.sh                  # Deploy Flink jobs
│   │   ├── reset-data.sh                   # Reset databases and topics
│   │   └── test-flow.sh                    # Test end-to-end flow
│   │
│   ├── tests/                              # Integration Tests
│   │   ├── test-kafka-connection.sh
│   │   ├── test-flink-jobs.sh
│   │   └── generate-test-events.py
│   │
│   ├── docs/                               # Documentation
│   │   ├── SETUP.md                        # Setup guide
│   │   ├── ARCHITECTURE.md                 # Architecture details
│   │   ├── JOBS.md                         # Flink jobs documentation
│   │   └── TROUBLESHOOTING.md              # Common issues & solutions
│   │
│   └── README.md                           # Data pipeline overview
│
├── KAFKA_SETUP.md                          # Laravel Kafka integration
├── KAFKA_FLINK_LOCAL_SETUP.md              # This file
└── ...
```

### 📂 Giải thích Folder Structure

**`data_pipeline/`** - Tất cả code và configs liên quan đến data processing
- **`docker/`** - Docker Compose và configs cho Kafka, Flink, PostgreSQL
- **`flink-jobs/`** - Source code của các Flink jobs (Java/Maven)
- **`scripts/`** - Helper scripts để quản lý pipeline
- **`tests/`** - Integration tests và test utilities
- **`docs/`** - Chi tiết documentation cho data pipeline

**Lợi ích:**
- ✅ Clear separation: Laravel app vs Data pipeline
- ✅ Easy to maintain và scale
- ✅ Independent development teams
- ✅ Có thể reuse data_pipeline cho projects khác
- ✅ Professional structure

## 🚀 Các bước chạy (Dự kiến)

### Bước 1: Setup Docker Environment

```bash
cd lensart_eyewear_backend/data_pipeline

# Start all services using helper script
./scripts/start-all.sh

# Hoặc start manual:
docker-compose -f docker/docker-compose.yml up -d

# Kiểm tra services đang chạy
docker-compose -f docker/docker-compose.yml ps
```

**Expected output:**
```
NAME                 STATUS    PORTS
zookeeper            Up        2181
kafka                Up        9092
kafka-ui             Up        8080
flink-jobmanager     Up        8081
flink-taskmanager    Up        
postgres             Up        5432
pgadmin              Up        5050
```

### Bước 2: Khởi tạo Kafka Topics

```bash
# Create all topics (REQUIRED - topics not auto-created)
docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-created \
  --bootstrap-server localhost:9092 \
  --partitions 3 \
  --replication-factor 1 \
  --if-not-exists

docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-updated \
  --bootstrap-server localhost:9092 \
  --partitions 3 \
  --replication-factor 1 \
  --if-not-exists

docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-cancelled \
  --bootstrap-server localhost:9092 \
  --partitions 3 \
  --replication-factor 1 \
  --if-not-exists

docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-events \
  --bootstrap-server localhost:9092 \
  --partitions 3 \
  --replication-factor 1 \
  --if-not-exists

docker exec kafka /usr/bin/kafka-topics --create \
  --topic order-events-dlq \
  --bootstrap-server localhost:9092 \
  --partitions 1 \
  --replication-factor 1 \
  --if-not-exists

# OR create all at once:
for topic in order-created order-updated order-cancelled order-events order-events-dlq; do
  docker exec kafka /usr/bin/kafka-topics --create \
    --topic $topic \
    --bootstrap-server localhost:9092 \
    --partitions 3 \
    --replication-factor 1 \
    --if-not-exists
done

# Verify topics đã được tạo:
docker exec kafka /usr/bin/kafka-topics --list --bootstrap-server localhost:9092
```

### Bước 3: Khởi tạo Database Schema

```bash
# PostgreSQL sẽ tự động chạy init.sql khi start lần đầu
# Verify database và tables:
docker exec -it postgres psql -U postgres -d lensart_events \
  -c "\dt"

# Hoặc re-initialize manual:
docker exec -it postgres psql -U postgres -d lensart_events \
  -f /docker-entrypoint-initdb.d/init.sql
```

### Bước 4: Build và Deploy Flink Jobs

```bash
# Build Flink jobs
cd data_pipeline/flink-jobs
mvn clean package

# Deploy tất cả jobs bằng script
cd ..
./scripts/deploy-jobs.sh

# Hoặc deploy manual từng job:
docker exec -it flink-jobmanager flink run \
  /opt/flink/jobs/OrderEventProcessor.jar

docker exec -it flink-jobmanager flink run \
  /opt/flink/jobs/OrderStatusTracker.jar

docker exec -it flink-jobmanager flink run \
  /opt/flink/jobs/RealTimeMetricsAggregator.jar

# Verify jobs đang chạy:
docker exec -it flink-jobmanager flink list
```

### Bước 5: Cấu hình Laravel

```bash
# Quay về root project
cd ../..

# Update .env với local Kafka config
echo "KAFKA_BROKERS=localhost:9092" >> .env

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### Bước 6: Test End-to-End Flow

```bash
# Terminal 1: Start Laravel API
php artisan serve

# Terminal 2: Test data pipeline
cd data_pipeline
./scripts/test-flow.sh

# Hoặc test manual:

# 1. Tạo order mới qua Laravel API (nếu cần)
curl -X POST http://localhost:8000/api/orders/create \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "branch_id": 1,
    "address": "123 Test Street",
    "order_details": [
      {
        "product_id": 1,
        "color": "Black",
        "quantity": 1,
        "total_price": 500000
      }
    ]
  }'

# 2. Gửi order created event to Kafka
curl -X POST http://localhost:8000/api/kafka/events/order-created \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"order_id": 1}'

# 3. Check Kafka UI
open http://localhost:8080

# 4. Check Flink Dashboard
open http://localhost:8081

# 5. Check PostgreSQL data
docker exec -it postgres psql -U postgres -d lensart_events \
  -c "SELECT * FROM orders_raw ORDER BY id DESC LIMIT 5;"

# 6. Check processed data
docker exec -it postgres psql -U postgres -d lensart_events \
  -c "SELECT * FROM orders_processed ORDER BY id DESC LIMIT 5;"

# 7. Check metrics
docker exec -it postgres psql -U postgres -d lensart_events \
  -c "SELECT * FROM order_metrics ORDER BY updated_at DESC LIMIT 5;"
```

## 🎨 Web Interfaces

| Service | URL | Purpose |
|---------|-----|---------|
| Kafka UI | http://localhost:8080 | Monitor Kafka topics, messages |
| Flink Dashboard | http://localhost:8081 | Monitor Flink jobs, metrics |
| PgAdmin | http://localhost:5050 | Manage PostgreSQL database |
| Laravel API | http://localhost:8000 | LensArt API endpoints |

## 📝 Flow Code sẽ implement

### Phase 1: Docker Infrastructure Setup
**Location:** `data_pipeline/docker/`

1. **docker-compose.yml**
   - Zookeeper service
   - Kafka broker với Kafka UI
   - Flink Job Manager & Task Manager
   - PostgreSQL với PgAdmin
   - Network configuration
   - Volume mounts

2. **kafka/kafka-topics.sh**
   - Script tạo topics: order-created, order-updated, order-cancelled, order-events
   - Auto-execute khi Kafka container start

3. **postgres/init.sql**
   - Create database `lensart_events`
   - Create 5 tables: orders_raw, orders_processed, order_status_history, order_metrics, order_items_analytics
   - Create indexes cho performance
   - Insert sample data (optional)

4. **.env.docker**
   - Environment variables cho các services
   - Kafka configs, PostgreSQL credentials, Flink settings

### Phase 2: Flink Jobs Development
**Location:** `data_pipeline/flink-jobs/`

1. **Setup Maven Project (pom.xml)**
   ```xml
   Dependencies:
   - Apache Flink 1.18.0
   - Flink Kafka Connector 3.0.0
   - PostgreSQL JDBC Driver 42.6.0
   - Jackson for JSON
   - Log4j2
   ```

2. **Job 1: OrderEventProcessor**
   - Source: Kafka topics (order-created, order-updated, order-cancelled)
   - Process: Deserialize, validate, enrich data
   - Sink: PostgreSQL (orders_raw, orders_processed)
   - Error handling & retry logic

3. **Job 2: OrderStatusTracker**
   - Source: Kafka (order-events)
   - Filter: event_type = "order.status_changed"
   - State: Track previous status per order
   - Process: Calculate processing time
   - Sink: PostgreSQL (order_status_history)

4. **Job 3: RealTimeMetricsAggregator**
   - Source: All order events
   - Window: Tumbling window (1 hour)
   - Aggregate: Count, sum, average by branch
   - Sink: PostgreSQL (order_metrics)
   - Update strategy: Upsert

5. **Supporting Classes**
   - Models: OrderEvent, OrderMetrics, OrderStatusHistory
   - Serializers: OrderEventDeserializer
   - Sinks: Custom PostgresSink with connection pooling
   - Utils: KafkaConfig, DatabaseConfig

### Phase 3: Helper Scripts
**Location:** `data_pipeline/scripts/`

1. **start-all.sh**
   ```bash
   - Start Docker Compose
   - Wait for services to be healthy
   - Verify Kafka topics created
   - Verify database initialized
   ```

2. **stop-all.sh**
   ```bash
   - Stop all Flink jobs gracefully
   - Stop Docker services
   - Clean up (optional)
   ```

3. **restart-all.sh**
   ```bash
   - Stop all services
   - Start all services
   - Redeploy Flink jobs
   ```

4. **deploy-jobs.sh**
   ```bash
   - Build Flink jobs (mvn package)
   - Copy JARs to Flink container
   - Submit jobs to Flink cluster
   - Verify jobs running
   ```

5. **reset-data.sh**
   ```bash
   - Delete Kafka topics
   - Truncate PostgreSQL tables
   - Recreate topics
   - Reseed data (optional)
   ```

6. **test-flow.sh**
   ```bash
   - Generate test events
   - Send to Laravel API
   - Verify data in Kafka
   - Verify data in PostgreSQL
   - Show success/failure report
   ```

### Phase 4: Integration Testing
**Location:** `data_pipeline/tests/`

1. **test-kafka-connection.sh**
   - Test Kafka broker connectivity
   - List topics
   - Produce/consume test messages

2. **test-flink-jobs.sh**
   - Check all jobs running
   - Verify job health
   - Check for exceptions in logs

3. **generate-test-events.py**
   - Python script to generate realistic order events
   - Send via Laravel API or directly to Kafka
   - Configurable event count and rate

### Phase 5: Documentation
**Location:** `data_pipeline/docs/`

1. **SETUP.md**
   - Prerequisites
   - Step-by-step setup guide
   - Environment configuration
   - Troubleshooting

2. **ARCHITECTURE.md**
   - System architecture diagram
   - Data flow explanation
   - Component details
   - Design decisions

3. **JOBS.md**
   - Each Flink job description
   - Input/output specifications
   - Configuration options
   - Performance tuning

4. **TROUBLESHOOTING.md**
   - Common issues and solutions
   - Debug commands
   - Log locations
   - Contact information

### Phase 6: Main README
**Location:** `data_pipeline/README.md`

- Overview of data pipeline
- Quick start guide
- Link to detailed docs
- Architecture diagram
- Team contacts

## 🔍 Monitoring & Debugging

### Check Kafka Messages
```bash
# Console consumer để xem messages
docker exec kafka /usr/bin/kafka-console-consumer \
  --bootstrap-server localhost:9092 \
  --topic order-created \
  --from-beginning

# Press Ctrl+C to stop
```

### Check Flink Logs
```bash
# Job Manager logs
docker logs flink-jobmanager

# Task Manager logs
docker logs flink-taskmanager
```

### Check PostgreSQL Data
```bash
# Connect to PostgreSQL
docker exec -it postgres psql -U postgres -d lensart_events

# Query data
SELECT COUNT(*) FROM orders_raw;
SELECT * FROM order_metrics ORDER BY updated_at DESC LIMIT 10;
```

## ⚠️ Lưu ý quan trọng

1. **Resource Requirements**:
   - RAM: Minimum 8GB (recommended 16GB)
   - CPU: Minimum 4 cores
   - Disk: 10GB free space

2. **Port Conflicts**: Đảm bảo các ports sau chưa được sử dụng:
   - 2181 (Zookeeper)
   - 9092 (Kafka)
   - 8080 (Kafka UI)
   - 8081 (Flink)
   - 5432 (PostgreSQL)
   - 5050 (PgAdmin)

3. **Development Flow**:
   - Develop Flink jobs locally
   - Build JAR files
   - Deploy to Docker Flink cluster
   - Test với real events từ Laravel

4. **Data Persistence**:
   - Kafka data: Docker volume `kafka-data`
   - PostgreSQL data: Docker volume `postgres-data`
   - Data sẽ persist khi restart containers

## 📦 Dependencies

### Laravel (Đã cài)
- nmred/kafka-php: ^0.1.6

### Flink Jobs (Sẽ cài)
- Apache Flink: 1.18.0
- Flink Kafka Connector: 3.0.0
- PostgreSQL JDBC Driver: 42.6.0

### Docker Images
- confluentinc/cp-kafka:7.5.0
- confluentinc/cp-zookeeper:7.5.0
- provectuslabs/kafka-ui:latest
- flink:1.18.0-scala_2.12
- postgres:16-alpine
- dpage/pgadmin4:latest

## 🎯 Success Criteria

Setup thành công khi:
- ✅ All Docker containers running
- ✅ Kafka topics created
- ✅ PostgreSQL database initialized
- ✅ Flink jobs deployed and running
- ✅ Laravel API có thể gửi events
- ✅ Events flow từ Laravel → Kafka → Flink → PostgreSQL
- ✅ Data xuất hiện trong PostgreSQL tables
- ✅ Web UIs accessible

## 📞 Troubleshooting

### Kafka không connect được
```bash
# Check Kafka logs
docker logs kafka

# Test connection
telnet localhost 9092
```

### Flink job failed
```bash
# Check job status
docker exec flink-jobmanager flink list

# Check logs
docker logs flink-jobmanager --tail 100
```

### PostgreSQL connection refused
```bash
# Check if PostgreSQL is running
docker exec postgres pg_isready -U postgres

# Check logs
docker logs postgres
```

---

## 👨‍💻 Development Workflow

### Làm việc với Laravel (Backend Team)
```bash
# Root project
cd lensart_eyewear_backend

# Develop Laravel features
php artisan serve

# Test Kafka integration
curl -X POST http://localhost:8000/api/kafka/events/order-created \
  -H "Authorization: Bearer TOKEN" \
  -d '{"order_id": 1}'
```

### Làm việc với Data Pipeline (Data Team)
```bash
# Data pipeline directory
cd lensart_eyewear_backend/data_pipeline

# Start infrastructure
./scripts/start-all.sh

# Develop Flink jobs
cd flink-jobs
# Edit Java code...
mvn clean package

# Deploy updated jobs
cd ..
./scripts/deploy-jobs.sh

# Test pipeline
./scripts/test-flow.sh

# Check logs
docker logs flink-jobmanager --tail 50

# Stop when done
./scripts/stop-all.sh
```

### Team Collaboration
```
Backend Team (app/)          Data Team (data_pipeline/)
      │                               │
      ├─ API Development             ├─ Docker Setup
      ├─ KafkaService                ├─ Flink Jobs
      ├─ Event Models                ├─ Database Schema
      └─ API Testing                 └─ Pipeline Testing
                │                     │
                └──── Integration ────┘
                    (Kafka Events)
```

## 📅 Timeline Thực hiện

| Phase | Task | Estimated Time |
|-------|------|----------------|
| 1 | Docker Infrastructure (docker-compose.yml, init scripts) | 45 mins |
| 2 | Database Schema & Init SQL | 30 mins |
| 3 | Helper Scripts (start, stop, deploy, test) | 45 mins |
| 4 | Flink Job 1 - OrderEventProcessor | 2.5 hours |
| 5 | Flink Job 2 - OrderStatusTracker | 1.5 hours |
| 6 | Flink Job 3 - RealTimeMetricsAggregator | 2 hours |
| 7 | Integration Testing & Bug Fixes | 1.5 hours |
| 8 | Documentation (SETUP, ARCHITECTURE, JOBS, TROUBLESHOOTING) | 1 hour |
| **Total** | | **~10 hours** |

## 🎯 Next Steps

### Immediate (Bắt đầu ngay)
1. ✅ Tạo folder structure `data_pipeline/`
2. ✅ Setup Docker Compose với Kafka + Flink + PostgreSQL
3. ✅ Tạo database schema (init.sql)
4. ✅ Tạo helper scripts (start-all.sh, stop-all.sh)
5. ✅ Test infrastructure locally

### Short-term (1-2 tuần)
1. ⏳ Develop Flink Job 1 (OrderEventProcessor)
2. ⏳ Test event flow: Laravel → Kafka → Flink → PostgreSQL
3. ⏳ Develop Flink Job 2 & 3
4. ⏳ Complete integration testing

### Long-term (Sau khi demo thành công)
1. 🔮 Add monitoring (Prometheus + Grafana)
2. 🔮 Implement CI/CD pipeline
3. 🔮 Deploy to Azure (AKS + Event Hubs)
4. 🔮 Scale testing với high volume
5. 🔮 Add more analytics features

---

## ✨ Kết luận

Với cấu trúc folder `data_pipeline/` mới:

**✅ Advantages:**
- Clear separation of concerns
- Independent development & deployment
- Easy to maintain and scale
- Professional project structure
- Reusable for other projects

**📂 Structure Summary:**
```
lensart_eyewear_backend/
├── app/                     # Laravel (Backend Team)
├── data_pipeline/           # Data Processing (Data Team)
│   ├── docker/              # Infrastructure as Code
│   ├── flink-jobs/          # Stream Processing Logic
│   ├── scripts/             # Automation Scripts
│   ├── tests/               # Integration Tests
│   └── docs/                # Detailed Documentation
└── ...
```

**🚀 Sẵn sàng để implement!**

Bạn có muốn tôi bắt đầu với Phase 1 (Docker Infrastructure Setup) không?

