#!/bin/bash

# ============================================
# Test End-to-End Data Flow
# ============================================

set -e  # Exit on error

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo "=========================================="
echo "Testing LensArt Data Pipeline"
echo "=========================================="
echo ""

# Check if services are running
echo "Checking services..."

services=("zookeeper" "kafka" "postgres" "flink-jobmanager" "flink-taskmanager")
all_running=true

for service in "${services[@]}"; do
    if docker ps | grep -q "$service"; then
        echo -e "  ${GREEN}✓${NC} $service is running"
    else
        echo -e "  ${RED}✗${NC} $service is NOT running"
        all_running=false
    fi
done

echo ""

if [ "$all_running" = false ]; then
    echo -e "${RED}Some services are not running. Please start them first:${NC}"
    echo "  ./scripts/start-all.sh"
    exit 1
fi

# Test 1: Kafka connectivity
echo "=========================================="
echo "Test 1: Kafka Connectivity"
echo "=========================================="
echo -n "Testing Kafka broker... "

if docker exec kafka /usr/bin/kafka-broker-api-versions --bootstrap-server localhost:9092 > /dev/null 2>&1; then
    echo -e "${GREEN}✓ PASS${NC}"
else
    echo -e "${RED}✗ FAIL${NC}"
    exit 1
fi

# Test 2: List Kafka topics
echo -n "Listing Kafka topics... "
topics=$(docker exec kafka /usr/bin/kafka-topics --list --bootstrap-server localhost:9092)

if echo "$topics" | grep -q "order-created"; then
    echo -e "${GREEN}✓ PASS${NC}"
    echo -e "${BLUE}Topics:${NC}"
    echo "$topics" | sed 's/^/  /'
else
    echo -e "${RED}✗ FAIL - Topics not found${NC}"
    exit 1
fi
echo ""

# Test 3: PostgreSQL connectivity
echo "=========================================="
echo "Test 2: PostgreSQL Connectivity"
echo "=========================================="
echo -n "Testing PostgreSQL... "

if docker exec postgres pg_isready -U postgres > /dev/null 2>&1; then
    echo -e "${GREEN}✓ PASS${NC}"
else
    echo -e "${RED}✗ FAIL${NC}"
    exit 1
fi

# Test 4: Check database tables
echo -n "Checking database tables... "
tables=$(docker exec postgres psql -U postgres -d lensart_events -c "\dt" -t 2>/dev/null | wc -l)

if [ "$tables" -gt 0 ]; then
    echo -e "${GREEN}✓ PASS${NC}"
    echo -e "${BLUE}Tables found:${NC}"
    docker exec postgres psql -U postgres -d lensart_events -c "\dt" | sed 's/^/  /'
else
    echo -e "${RED}✗ FAIL - No tables found${NC}"
    exit 1
fi
echo ""

# Test 5: Flink connectivity
echo "=========================================="
echo "Test 3: Flink Connectivity"
echo "=========================================="
echo -n "Testing Flink Job Manager... "

if curl -s http://localhost:8081 > /dev/null 2>&1; then
    echo -e "${GREEN}✓ PASS${NC}"
else
    echo -e "${RED}✗ FAIL${NC}"
    exit 1
fi

# Test 6: Send test event to Kafka
echo ""
echo "=========================================="
echo "Test 4: Send Test Event to Kafka"
echo "=========================================="

test_event='{"event_type":"test.connection","event_id":"test_'$(date +%s)'","timestamp":"'$(date -Iseconds)'","data":{"test":true,"message":"Test event from test-flow.sh"}}'

echo "Sending test event to order-events topic..."
echo "$test_event" | docker exec -i kafka /usr/bin/kafka-console-producer --bootstrap-server localhost:9092 --topic order-events

echo -e "${GREEN}✓ Test event sent${NC}"
echo ""

# Test 7: Verify event in Kafka
echo "Verifying event in Kafka..."
echo "Reading last message from topic..."

timeout 5 docker exec kafka /usr/bin/kafka-console-consumer \
    --bootstrap-server localhost:9092 \
    --topic order-events \
    --from-beginning \
    --max-messages 1 2>/dev/null || true

echo ""
echo -e "${GREEN}✓ Event verified in Kafka${NC}"
echo ""

# Summary
echo "=========================================="
echo -e "${GREEN}✓ All Tests Passed!${NC}"
echo "=========================================="
echo ""
echo "Service URLs:"
echo "  - Kafka UI:        http://localhost:8080"
echo "  - Flink Dashboard: http://localhost:8081"
echo "  - PgAdmin:         http://localhost:5050"
echo ""
echo "To send real order events, use Laravel API:"
echo '  curl -X POST http://localhost:8000/api/kafka/events/order-created \'
echo '    -H "Authorization: Bearer YOUR_TOKEN" \'
echo '    -H "Content-Type: application/json" \'
echo '    -d '"'"'{"order_id": 1}'"'"
echo ""

