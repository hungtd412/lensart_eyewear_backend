# LensArt Flink Jobs

Real-time sales transaction processing with Apache Flink.

---

## 📋 Overview

This module contains Apache Flink jobs for processing sales transactions from Kafka and storing them in PostgreSQL.

```
Kafka (order-created topic)
        ↓
   [Flink Job]
   - Parse JSON
   - Validate
   - Transform
        ↓
PostgreSQL (sales_transactions table)
```

---

## 🏗️ Architecture

### Job: SalesTransactionJob

**Purpose:** Process sales transactions in real-time

**Input:** Kafka topic `order-created`
- Format: JSON with 6 fields
- Partitions: 3
- Consumer Group: `sales-transaction-processor`

**Processing:**
1. Deserialize JSON → `SalesTransaction` object
2. Validate data (quantity > 0, price >= 0, etc.)
3. Filter out invalid transactions
4. Batch write to PostgreSQL

**Output:** PostgreSQL table `sales_transactions`
- Batch size: 100 records
- Batch interval: 1 second
- Max retries: 3

**Features:**
- Exactly-once semantics (via checkpoints)
- Fault tolerance (60-second checkpoints)
- Error handling (invalid data filtered out)
- Configurable via environment variables

---

## 📦 Project Structure

```
flink-jobs/
├── pom.xml                                    # Maven configuration
├── README.md                                  # This file
│
├── src/
│   └── main/
│       ├── java/
│       │   └── com/lensart/pipeline/
│       │       ├── SalesTransactionJob.java   # Main Flink job
│       │       └── models/
│       │           └── SalesTransaction.java  # Data model
│       │
│       └── resources/
│           └── log4j2.properties              # Logging config
│
└── target/                                    # Build output (generated)
    └── lensart-sales-pipeline-1.0.0.jar      # Deployable JAR
```

---

## 🔧 Prerequisites

### Required
- **Java 11+** ([Download](https://adoptium.net/))
- **Maven 3.6+** ([Download](https://maven.apache.org/download.cgi))
- **Docker** (for local Flink cluster)

### Verify Installation

```bash
# Check Java
java -version
# Should show: openjdk version "11.0.x" or higher

# Check Maven
mvn -version
# Should show: Apache Maven 3.6.x or higher

# Check Docker
docker --version
```

---

## 🚀 Quick Start

### 1. Build the Project

```bash
cd data_pipeline/flink-jobs

# Clean and build
mvn clean package

# Output: target/lensart-sales-pipeline-1.0.0.jar
```

### 2. Deploy to Flink

```bash
cd ../scripts

# Deploy using automated script
./deploy-sales-job.sh

# Or deploy manually (see below)
```

### 3. Verify Deployment

- **Flink UI:** http://localhost:8081
- Check "Running Jobs" tab
- Job name: "LensArt Sales Transaction Processor"

---

## 📝 Configuration

### Environment Variables

Configure the job via environment variables:

| Variable | Default | Description |
|----------|---------|-------------|
| `KAFKA_BOOTSTRAP_SERVERS` | `kafka:29092` | Kafka broker address |
| `KAFKA_TOPIC` | `order-created` | Kafka topic to consume |
| `KAFKA_GROUP_ID` | `sales-transaction-processor` | Consumer group ID |
| `POSTGRES_URL` | `jdbc:postgresql://postgres:5432/lensart_events` | PostgreSQL JDBC URL |
| `POSTGRES_USER` | `postgres` | Database username |
| `POSTGRES_PASSWORD` | `postgres` | Database password |
| `CHECKPOINT_INTERVAL` | `60000` | Checkpoint interval (ms) |
| `JDBC_BATCH_SIZE` | `100` | JDBC batch size |
| `JDBC_BATCH_INTERVAL_MS` | `1000` | JDBC batch interval (ms) |

### Example: Custom Configuration

```bash
# Set environment variables
docker exec flink-jobmanager bash -c '
  export KAFKA_BOOTSTRAP_SERVERS=my-kafka:9092 && \
  export JDBC_BATCH_SIZE=200 && \
  flink run -d /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar
'
```

---

## 🛠️ Development

### Project Setup

```bash
# Clone/navigate to project
cd data_pipeline/flink-jobs

# Import into IDE (IntelliJ IDEA, Eclipse, VSCode)
# IDE will auto-detect Maven project
```

### Build Commands

```bash
# Clean build
mvn clean package

# Skip tests
mvn clean package -DskipTests

# Verbose output
mvn clean package -X

# Specific profile
mvn clean package -P production
```

### Local Testing

```bash
# Run job locally (without Docker)
mvn exec:java -Dexec.mainClass="com.lensart.pipeline.SalesTransactionJob"

# Note: Requires local Kafka and PostgreSQL
```

---

## 📊 Deployment

### Option 1: Automated Script (Recommended)

```bash
cd data_pipeline/scripts

# Build and deploy
./deploy-sales-job.sh

# Deploy without rebuilding
./deploy-sales-job.sh --skip-build
```

### Option 2: Manual Deployment

```bash
# 1. Build JAR
cd data_pipeline/flink-jobs
mvn clean package

# 2. Copy JAR to Flink container
docker cp target/lensart-sales-pipeline-1.0.0.jar \
  flink-jobmanager:/opt/flink/usrlib/

# 3. Submit job
docker exec flink-jobmanager flink run \
  -d \
  -c com.lensart.pipeline.SalesTransactionJob \
  /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar

# 4. Check status
docker exec flink-jobmanager flink list
```

### Option 3: Via Flink Web UI

1. Open http://localhost:8081
2. Click **"Submit New Job"**
3. Click **"+ Add New"**
4. Upload `lensart-sales-pipeline-1.0.0.jar`
5. Select entry class: `com.lensart.pipeline.SalesTransactionJob`
6. Click **"Submit"**

---

## 🧪 Testing

### Send Test Transaction

```bash
# Via Laravel API
curl -X POST http://localhost:8000/api/kafka/transactions/sales \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"order_id": 1}'
```

### Verify in Kafka

```bash
# Check Kafka UI
open http://localhost:8080

# Or use command line
docker exec kafka kafka-console-consumer.sh \
  --bootstrap-server localhost:9092 \
  --topic order-created \
  --from-beginning \
  --max-messages 5
```

### Verify in PostgreSQL

```bash
# Query sales_transactions table
docker exec -it postgres psql -U postgres -d lensart_events \
  -c "SELECT * FROM sales_transactions ORDER BY created_at DESC LIMIT 10;"

# Count transactions
docker exec -it postgres psql -U postgres -d lensart_events \
  -c "SELECT COUNT(*) FROM sales_transactions;"
```

---

## 🐛 Troubleshooting

### Build Fails

**Error:** `Failed to execute goal org.apache.maven.plugins:maven-compiler-plugin`

**Solution:**
```bash
# Check Java version (must be 11+)
java -version

# Set JAVA_HOME
export JAVA_HOME=/path/to/java11
```

---

### Job Fails to Start

**Error:** `Connection refused: kafka:29092`

**Solution:**
```bash
# Check Kafka is running
docker ps | grep kafka

# Check network
docker exec flink-jobmanager ping kafka
```

---

### No Data in PostgreSQL

**Check:**
1. Flink job is running: http://localhost:8081
2. No exceptions in logs: `docker logs flink-jobmanager`
3. Events in Kafka: http://localhost:8080
4. Table exists: `\dt sales_transactions`

---

### Out of Memory

**Error:** `java.lang.OutOfMemoryError: Java heap space`

**Solution:** Increase Flink TaskManager memory in `docker-compose.yml`:

```yaml
flink-taskmanager:
  environment:
    - taskmanager.memory.process.size: 4096m  # Increase from 2048m
```

---

## 📚 Dependencies

### Core Dependencies

- **Apache Flink** 1.18.0 - Stream processing framework
- **Flink Kafka Connector** 3.0.0 - Kafka integration
- **Flink JDBC Connector** 3.1.1 - Database integration
- **PostgreSQL Driver** 42.6.0 - PostgreSQL connectivity
- **Jackson** 2.15.2 - JSON processing

### See `pom.xml` for complete list

---

## 🔍 Monitoring

### Flink Dashboard

**URL:** http://localhost:8081

**Metrics to watch:**
- Records/second (throughput)
- Checkpoint success rate
- Backpressure
- Task Manager CPU/memory

### Check Job Status

```bash
# List running jobs
docker exec flink-jobmanager flink list

# List all jobs (including completed)
docker exec flink-jobmanager flink list -a
```

### View Logs

```bash
# JobManager logs
docker logs flink-jobmanager -f

# TaskManager logs
docker logs flink-taskmanager -f

# Filter for errors
docker logs flink-jobmanager 2>&1 | grep ERROR
```

### Cancel Job

```bash
# Get job ID
JOB_ID=$(docker exec flink-jobmanager flink list -r | grep "Sales Transaction" | awk '{print $4}')

# Cancel job
docker exec flink-jobmanager flink cancel $JOB_ID

# Cancel with savepoint
docker exec flink-jobmanager flink cancel -s /tmp/savepoints $JOB_ID
```

---

## 🚀 Performance Tuning

### Increase Parallelism

```bash
# Deploy with specific parallelism
docker exec flink-jobmanager flink run \
  -d \
  -p 4 \
  -c com.lensart.pipeline.SalesTransactionJob \
  /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar
```

### Optimize JDBC Batch

Larger batch = fewer DB writes (better performance)
Smaller batch = lower latency

```bash
# Set via environment
export JDBC_BATCH_SIZE=200
export JDBC_BATCH_INTERVAL_MS=2000
```

### Tune Checkpoints

```bash
# Increase checkpoint interval (less overhead)
export CHECKPOINT_INTERVAL=120000  # 2 minutes
```

---

## 📖 Further Reading

- [Apache Flink Documentation](https://nightlies.apache.org/flink/flink-docs-release-1.18/)
- [Kafka Connector Guide](https://nightlies.apache.org/flink/flink-docs-release-1.18/docs/connectors/datastream/kafka/)
- [JDBC Connector Guide](https://nightlies.apache.org/flink/flink-docs-release-1.18/docs/connectors/datastream/jdbc/)
- [Production Checklist](https://nightlies.apache.org/flink/flink-docs-release-1.18/docs/ops/production_ready/)

---

## 📞 Support

### Common Issues

See [Troubleshooting](#-troubleshooting) section above.

### Logs

Check `docker logs flink-jobmanager` for detailed error messages.

### Debug Mode

Enable DEBUG logging in `log4j2.properties`:

```properties
logger.lensart.level = DEBUG
```

---

**Version:** 1.0.0  
**Last Updated:** 22/11/2024  
**Flink Version:** 1.18.0  
**Java Version:** 11+

---

**Ready to deploy? Run:** `./scripts/deploy-sales-job.sh` 🚀

