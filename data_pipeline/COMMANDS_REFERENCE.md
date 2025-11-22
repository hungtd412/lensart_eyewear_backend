# Commands Reference - Quick Cheat Sheet

**Quick reference for all common commands**

---

## 🚀 Start/Stop Services

### Start All
```bash
cd data_pipeline/docker
docker-compose up -d
```

### Stop All
```bash
cd data_pipeline/docker
docker-compose down
```

### Restart All
```bash
cd data_pipeline/docker
docker-compose restart
```

### Check Status
```bash
docker-compose ps
```

---

## 📨 Kafka Commands

### Create Topics (DO THIS FIRST!)
```bash
# Create all topics at once
for topic in order-created order-updated order-cancelled order-events order-events-dlq; do
  docker exec kafka /usr/bin/kafka-topics --create \
    --topic $topic \
    --bootstrap-server localhost:9092 \
    --partitions 3 \
    --replication-factor 1 \
    --if-not-exists
done
```

### List Topics
```bash
docker exec kafka /usr/bin/kafka-topics --list --bootstrap-server localhost:9092
```

### Describe Topic
```bash
docker exec kafka /usr/bin/kafka-topics --describe \
  --bootstrap-server localhost:9092 \
  --topic order-created
```

### Send Test Message
```bash
echo '{"test": "message"}' | docker exec -i kafka /usr/bin/kafka-console-producer \
  --bootstrap-server localhost:9092 \
  --topic order-events
```

### Read Messages
```bash
# Read from beginning
docker exec kafka /usr/bin/kafka-console-consumer \
  --bootstrap-server localhost:9092 \
  --topic order-created \
  --from-beginning

# Read last 10 messages
docker exec kafka /usr/bin/kafka-console-consumer \
  --bootstrap-server localhost:9092 \
  --topic order-created \
  --from-beginning \
  --max-messages 10
```

### Delete Topic
```bash
docker exec kafka /usr/bin/kafka-topics --delete \
  --bootstrap-server localhost:9092 \
  --topic topic-name
```

---

## 🗄️ PostgreSQL Commands

### Connect to Database
```bash
docker exec -it postgres psql -U postgres -d lensart_events
```

### Inside psql:
```sql
-- List tables
\dt

-- Describe table
\d orders_raw

-- Count records
SELECT COUNT(*) FROM orders_raw;

-- View recent events
SELECT * FROM orders_raw ORDER BY id DESC LIMIT 5;

-- View full JSON data
SELECT event_id, event_type, event_data FROM orders_raw LIMIT 1;

-- Exit
\q
```

### Quick Query (from outside)
```bash
# Count records
docker exec postgres psql -U postgres -d lensart_events \
  -c "SELECT COUNT(*) FROM orders_raw;"

# View recent
docker exec postgres psql -U postgres -d lensart_events \
  -c "SELECT * FROM orders_raw ORDER BY id DESC LIMIT 5;"

# View metrics
docker exec postgres psql -U postgres -d lensart_events \
  -c "SELECT * FROM order_metrics ORDER BY updated_at DESC LIMIT 10;"
```

---

## 🔧 Flink Commands

### List Running Jobs
```bash
docker exec flink-jobmanager flink list
```

### Submit Job
```bash
docker exec flink-jobmanager flink run /opt/flink/jobs/JobName.jar
```

### Cancel Job
```bash
docker exec flink-jobmanager flink cancel <JOB_ID>
```

### Stop Job with Savepoint
```bash
docker exec flink-jobmanager flink stop <JOB_ID>
```

---

## 🌐 Laravel Commands

### Start Laravel
```bash
# From project root
php artisan serve
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Test Kafka Connection
```bash
curl http://localhost:8000/api/kafka/test-connection
```

### Send Order Event
```bash
curl -X POST http://localhost:8000/api/kafka/events/order-created \
  -H "Content-Type: application/json" \
  -d '{"order_id": 1}'
```

---

## 📊 View Logs

### All Services
```bash
cd data_pipeline/docker
docker-compose logs -f
```

### Specific Service
```bash
# Kafka
docker logs kafka --tail 50 -f

# PostgreSQL
docker logs postgres --tail 50 -f

# Flink Job Manager
docker logs flink-jobmanager --tail 50 -f

# Flink Task Manager
docker logs flink-taskmanager --tail 50 -f
```

---

## 🧹 Cleanup Commands

### Remove Stopped Containers
```bash
docker container prune
```

### Remove Unused Images
```bash
docker image prune -a
```

### Remove All Volumes (⚠️ DATA LOSS!)
```bash
cd data_pipeline/docker
docker-compose down -v
```

### Complete Cleanup (⚠️ EVERYTHING!)
```bash
docker system prune -a --volumes
```

---

## 🔍 Health Checks

### Check Kafka
```bash
docker exec kafka /usr/bin/kafka-broker-api-versions --bootstrap-server localhost:9092
```

### Check PostgreSQL
```bash
docker exec postgres pg_isready -U postgres
```

### Check Flink
```bash
curl -s http://localhost:8081/overview
```

---

## 🌐 Web Interfaces

| Service | URL | Credentials |
|---------|-----|-------------|
| Kafka UI | http://localhost:8080 | - |
| Flink Dashboard | http://localhost:8081 | - |
| PgAdmin | http://localhost:5050 | admin@lensart.com / admin |
| Laravel API | http://localhost:8000 | - |

---

## 🐛 Troubleshooting

### Kafka not responding
```bash
# Check if running
docker ps | grep kafka

# Restart Kafka
docker restart kafka

# Check logs
docker logs kafka --tail 50
```

### PostgreSQL connection refused
```bash
# Check if ready
docker exec postgres pg_isready -U postgres

# Restart
docker restart postgres
```

### Port already in use
```bash
# Windows
netstat -ano | findstr "9092"

# Linux/Mac
lsof -i :9092

# Stop the process using the port
```

### Docker out of resources
```bash
# Check disk space
docker system df

# Clean up
docker system prune
```

---

## 📝 Useful Aliases (Optional)

Add to your `.bashrc` or `.zshrc`:

```bash
# Data Pipeline shortcuts
alias dp-start='cd data_pipeline/docker && docker-compose up -d'
alias dp-stop='cd data_pipeline/docker && docker-compose down'
alias dp-logs='cd data_pipeline/docker && docker-compose logs -f'
alias dp-ps='cd data_pipeline/docker && docker-compose ps'

# Kafka shortcuts
alias kafka-topics='docker exec kafka /usr/bin/kafka-topics --bootstrap-server localhost:9092'
alias kafka-consume='docker exec kafka /usr/bin/kafka-console-consumer --bootstrap-server localhost:9092'
alias kafka-produce='docker exec -i kafka /usr/bin/kafka-console-producer --bootstrap-server localhost:9092'

# PostgreSQL shortcut
alias psql-lensart='docker exec -it postgres psql -U postgres -d lensart_events'
```

---

**Last Updated**: 2024-11-18

