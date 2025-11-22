# Quick Start Guide

## 🚀 Start in 30 seconds

```bash
cd data_pipeline
./scripts/start-all.sh
```

**Note for Windows users**: If you get permission errors, run the scripts manually:

```bash
cd data_pipeline/docker
docker-compose up -d
```

## ✅ Verify Setup

Open in browser:
- **Kafka UI**: http://localhost:8080
- **Flink Dashboard**: http://localhost:8081  
- **PgAdmin**: http://localhost:5050

## 📝 Test Event Flow

```bash
# From data_pipeline directory
./scripts/test-flow.sh
```

## 🛑 Stop Services

```bash
./scripts/stop-all.sh
```

## 📚 Full Documentation

See [README.md](README.md) for complete documentation.

