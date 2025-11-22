#!/bin/bash

# ============================================
# Start All Data Pipeline Services
# ============================================

set -e  # Exit on error

echo "=========================================="
echo "Starting LensArt Data Pipeline"
echo "=========================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DOCKER_DIR="$SCRIPT_DIR/../docker"

# Change to docker directory
cd "$DOCKER_DIR"

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}✗ Docker is not running. Please start Docker first.${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Docker is running${NC}"
echo ""

# Stop existing containers (if any)
echo "Stopping existing containers..."
docker-compose down 2>/dev/null || true
echo ""

# Start all services
echo "Starting all services..."
echo ""
docker-compose up -d

# Wait for services to be ready
echo ""
echo "=========================================="
echo "Waiting for services to be ready..."
echo "=========================================="
echo ""

# Wait for Zookeeper
echo -n "Waiting for Zookeeper..."
for i in {1..30}; do
    if docker exec zookeeper bash -c "echo ruok | nc localhost 2181" 2>/dev/null | grep -q imok; then
        echo -e " ${GREEN}✓ Ready${NC}"
        break
    fi
    echo -n "."
    sleep 2
done
echo ""

# Wait for Kafka
echo -n "Waiting for Kafka..."
for i in {1..30}; do
    if docker exec kafka kafka-broker-api-versions --bootstrap-server localhost:9092 > /dev/null 2>&1; then
        echo -e " ${GREEN}✓ Ready${NC}"
        break
    fi
    echo -n "."
    sleep 2
done
echo ""

# Wait for PostgreSQL
echo -n "Waiting for PostgreSQL..."
for i in {1..30}; do
    if docker exec postgres pg_isready -U postgres > /dev/null 2>&1; then
        echo -e " ${GREEN}✓ Ready${NC}"
        break
    fi
    echo -n "."
    sleep 2
done
echo ""

# Wait for Flink Job Manager
echo -n "Waiting for Flink Job Manager..."
for i in {1..30}; do
    if curl -s http://localhost:8081 > /dev/null 2>&1; then
        echo -e " ${GREEN}✓ Ready${NC}"
        break
    fi
    echo -n "."
    sleep 2
done
echo ""

# Initialize Kafka topics
echo ""
echo "=========================================="
echo "Initializing Kafka Topics..."
echo "=========================================="
echo ""

sleep 5

# Create all Kafka topics
echo "Creating Kafka topics..."
for topic in order-created order-updated order-cancelled order-events order-events-dlq; do
  docker exec kafka /usr/bin/kafka-topics --create \
    --topic $topic \
    --bootstrap-server localhost:9092 \
    --partitions 3 \
    --replication-factor 1 \
    --if-not-exists 2>/dev/null
done

echo -e "${GREEN}✓ Kafka topics created${NC}"

# Show running services
echo ""
echo "=========================================="
echo "Running Services:"
echo "=========================================="
docker-compose ps
echo ""

# Show service URLs
echo "=========================================="
echo "Service URLs:"
echo "=========================================="
echo -e "${GREEN}✓ Kafka UI:${NC}         http://localhost:8080"
echo -e "${GREEN}✓ Flink Dashboard:${NC}  http://localhost:8081"
echo -e "${GREEN}✓ PgAdmin:${NC}          http://localhost:5050"
echo -e "  ${YELLOW}(Email: admin@lensart.com, Password: admin)${NC}"
echo ""
echo -e "${GREEN}✓ Kafka Broker:${NC}     localhost:9092"
echo -e "${GREEN}✓ PostgreSQL:${NC}       localhost:5432"
echo -e "  ${YELLOW}(User: postgres, Password: postgres, DB: lensart_events)${NC}"
echo ""

echo "=========================================="
echo -e "${GREEN}✓ Data Pipeline Started Successfully!${NC}"
echo "=========================================="
echo ""
echo "To stop all services, run:"
echo "  ./scripts/stop-all.sh"
echo ""
echo "To view logs, run:"
echo "  docker-compose -f docker/docker-compose.yml logs -f [service-name]"
echo ""

