# LensArt Data Pipeline

Real-time data processing pipeline for LensArt Eyewear using **Apache Kafka**, **Apache Flink**, and **PostgreSQL**.

## 📋 Overview

This data pipeline processes order events in real-time, performing ETL operations and generating analytics metrics.

```
Laravel API → Kafka → Flink → PostgreSQL
```

## 🏗️ Architecture

- **Apache Kafka**: Event streaming platform for order events
- **Apache Flink**: Stream processing engine for real-time ETL
- **PostgreSQL**: Data warehouse for processed data and analytics
- **Kafka UI**: Web interface for monitoring Kafka
- **PgAdmin**: Web interface for PostgreSQL management

## 📁 Project Structure

```
data_pipeline/
├── docker/                     # Docker infrastructure
│   ├── docker-compose.yml      # All services definition
│   ├── kafka/
│   │   └── kafka-topics.sh     # Kafka topics initialization
│   └── postgres/
│       └── init.sql            # Database schema
│
├── flink-jobs/                 # Flink jobs source code (Java/Maven)
│   ├── pom.xml
│   └── src/main/java/com/lensart/pipeline/
│
├── scripts/                    # Helper scripts
│   ├── start-all.sh            # Start all services
│   ├── stop-all.sh             # Stop all services
│   ├── restart-all.sh          # Restart all services
│   ├── deploy-jobs.sh          # Deploy Flink jobs
│   ├── reset-data.sh           # Reset all data (WARNING!)
│   └── test-flow.sh            # Test end-to-end flow
│
├── tests/                      # Integration tests
├── docs/                       # Detailed documentation
└── README.md                   # This file
```

## 🚀 Quick Start

### Prerequisites

- Docker & Docker Compose
- 8GB+ RAM recommended
- Ports available: 2181, 9092, 8080, 8081, 5432, 5050

### Start the Pipeline

```bash
cd data_pipeline

# Start all services (one command!)
./scripts/start-all.sh
```

This will:
- ✅ Start Zookeeper, Kafka, PostgreSQL, Flink
- ✅ Create Kafka topics automatically
- ✅ Initialize database schema
- ✅ Wait for all services to be healthy

### Access Web Interfaces

| Service | URL | Credentials |
|---------|-----|-------------|
| **Kafka UI** | http://localhost:8080 | - |
| **Flink Dashboard** | http://localhost:8081 | - |
| **PgAdmin** | http://localhost:5050 | admin@lensart.com / admin |

### PostgreSQL Connection

- **Host**: localhost
- **Port**: 5432
- **Database**: lensart_events
- **User**: postgres
- **Password**: postgres

## 🔧 Commands

### Start/Stop Services

```bash
# Start all services
./scripts/start-all.sh

# Stop all services
./scripts/stop-all.sh

# Restart all services
./scripts/restart-all.sh
```

### Deploy Flink Jobs

```bash
# Build and deploy all Flink jobs
./scripts/deploy-jobs.sh

# Or deploy manually
cd flink-jobs
mvn clean package
# Then submit JARs via Flink UI or CLI
```

### Test Data Flow

```bash
# Run end-to-end tests
./scripts/test-flow.sh
```

### Reset Data (⚠️ Destructive!)

```bash
# Delete ALL data and start fresh
./scripts/reset-data.sh
```

### View Logs

```bash
cd docker

# All services
docker-compose logs -f

# Specific service
docker-compose logs -f kafka
docker-compose logs -f flink-jobmanager
docker-compose logs -f postgres
```

## 📊 Data Flow

### 1. Order Created

```
1. Laravel API receives order creation request
2. KafkaService sends event to "order-created" topic
3. Flink OrderEventProcessor consumes event
4. Processes and enriches data
5. Writes to PostgreSQL:
   - orders_raw (raw event)
   - orders_processed (cleaned data)
   - order_items_analytics (product details)
```

### 2. Order Status Changed

```
1. Admin changes order status in Laravel
2. Event sent to "order-events" topic
3. Flink OrderStatusTracker consumes event
4. Tracks status change history
5. Calculates processing time
6. Writes to order_status_history table
```

### 3. Real-time Metrics

```
1. Flink RealTimeMetricsAggregator processes all events
2. Aggregates by hour and branch:
   - Total orders
   - Total revenue
   - Average order value
   - Orders by status
3. Updates order_metrics table (upsert)
```

## 🗄️ Database Tables

| Table | Purpose |
|-------|---------|
| `orders_raw` | Raw events from Kafka (immutable log) |
| `orders_processed` | Cleaned and enriched order data |
| `order_status_history` | Order status change tracking |
| `order_metrics` | Aggregated metrics (hourly/daily) |
| `order_items_analytics` | Product-level analytics |

## 🔍 Monitoring

### Check Service Health

```bash
# Kafka
docker exec kafka kafka-broker-api-versions --bootstrap-server localhost:9092

# PostgreSQL
docker exec postgres pg_isready -U postgres

# Flink
curl http://localhost:8081
```

### Check Kafka Topics

```bash
# List topics
docker exec kafka kafka-topics.sh --list --bootstrap-server localhost:9092

# Describe topic
docker exec kafka kafka-topics.sh --describe --bootstrap-server localhost:9092 --topic order-created

# Consume messages
docker exec kafka kafka-console-consumer.sh \
  --bootstrap-server localhost:9092 \
  --topic order-created \
  --from-beginning
```

### Check Database

```bash
# Connect to PostgreSQL
docker exec -it postgres psql -U postgres -d lensart_events

# List tables
\dt

# Query data
SELECT COUNT(*) FROM orders_raw;
SELECT * FROM order_metrics ORDER BY updated_at DESC LIMIT 10;
```

### Check Flink Jobs

```bash
# List running jobs
docker exec flink-jobmanager flink list

# Cancel a job
docker exec flink-jobmanager flink cancel <JOB_ID>
```

## 🧪 Testing

### Send Test Event

```bash
# Via test script
./scripts/test-flow.sh

# Or manually via Laravel API
curl -X POST http://localhost:8000/api/kafka/events/order-created \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"order_id": 1}'
```

### Verify Data Flow

```bash
# 1. Check Kafka UI
open http://localhost:8080

# 2. Check Flink Dashboard
open http://localhost:8081

# 3. Check PostgreSQL data
docker exec postgres psql -U postgres -d lensart_events \
  -c "SELECT * FROM orders_raw ORDER BY id DESC LIMIT 5;"
```

## 📚 Documentation

- [Setup Guide](docs/SETUP.md) - Detailed setup instructions
- [Architecture](docs/ARCHITECTURE.md) - System architecture details
- [Flink Jobs](docs/JOBS.md) - Flink jobs documentation
- [Troubleshooting](docs/TROUBLESHOOTING.md) - Common issues & solutions

## 🐛 Troubleshooting

### Services won't start

```bash
# Check if ports are in use
netstat -ano | findstr "9092"  # Windows
lsof -i :9092                  # Mac/Linux

# Check Docker resources
docker system df
docker system prune  # Clean up if needed
```

### Kafka connection failed

```bash
# Check Kafka logs
docker logs kafka --tail 50

# Restart Kafka
docker-compose restart kafka
```

### PostgreSQL connection failed

```bash
# Check PostgreSQL logs
docker logs postgres --tail 50

# Verify database exists
docker exec postgres psql -U postgres -l
```

### Flink job failed

```bash
# Check job logs in Flink UI
open http://localhost:8081

# Or via CLI
docker logs flink-jobmanager --tail 100
docker logs flink-taskmanager --tail 100
```

## 🛠️ Development

### Develop Flink Jobs

```bash
cd flink-jobs

# Edit Java files in src/main/java/

# Build
mvn clean package

# Deploy
../scripts/deploy-jobs.sh
```

### Add New Kafka Topic

Edit `docker/kafka/kafka-topics.sh` and add:

```bash
create_topic "new-topic-name" 3 1 168
```

Then restart:

```bash
./scripts/restart-all.sh
```

## 📝 TODO

- [ ] Implement Flink Job 1: OrderEventProcessor
- [ ] Implement Flink Job 2: OrderStatusTracker
- [ ] Implement Flink Job 3: RealTimeMetricsAggregator
- [ ] Add monitoring with Prometheus + Grafana
- [ ] Add CI/CD pipeline
- [ ] Performance testing
- [ ] Deploy to Azure (AKS + Event Hubs)

## 🤝 Contributing

1. Create feature branch
2. Make changes
3. Test locally with `./scripts/test-flow.sh`
4. Submit pull request

## 📞 Support

- Documentation: `docs/`
- Issues: Check `docs/TROUBLESHOOTING.md`
- Logs: `docker-compose logs -f [service]`

## 📜 License

Internal project for LensArt Eyewear.

---

**Version:** 1.0.0  
**Last Updated:** 2024-11-17

