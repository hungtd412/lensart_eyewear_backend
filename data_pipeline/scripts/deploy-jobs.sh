#!/bin/bash

# ============================================
# Deploy Flink Jobs to Cluster
# ============================================

set -e  # Exit on error

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo "=========================================="
echo "Deploying Flink Jobs"
echo "=========================================="
echo ""

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
FLINK_JOBS_DIR="$SCRIPT_DIR/../flink-jobs"

# Check if Flink is running
if ! docker ps | grep -q flink-jobmanager; then
    echo -e "${RED}✗ Flink Job Manager is not running${NC}"
    echo "Please start the data pipeline first:"
    echo "  ./scripts/start-all.sh"
    exit 1
fi

# Build Flink jobs if not already built
if [ ! -d "$FLINK_JOBS_DIR/target" ]; then
    echo "Building Flink jobs..."
    cd "$FLINK_JOBS_DIR"
    
    if [ ! -f "pom.xml" ]; then
        echo -e "${YELLOW}⚠ No pom.xml found. Flink jobs not yet created.${NC}"
        echo "This script will deploy jobs once they are built."
        exit 0
    fi
    
    mvn clean package -DskipTests
    echo -e "${GREEN}✓ Build complete${NC}"
    echo ""
else
    echo -e "${YELLOW}Using existing build in target/${NC}"
    echo ""
fi

# Deploy jobs
cd "$SCRIPT_DIR"

echo "Deploying jobs to Flink cluster..."
echo ""

# Job 1: OrderEventProcessor
if [ -f "$FLINK_JOBS_DIR/target/OrderEventProcessor.jar" ]; then
    echo "Deploying OrderEventProcessor..."
    docker exec flink-jobmanager flink run /opt/flink/jobs/OrderEventProcessor.jar
    echo -e "${GREEN}✓ OrderEventProcessor deployed${NC}"
    echo ""
else
    echo -e "${YELLOW}⚠ OrderEventProcessor.jar not found${NC}"
fi

# Job 2: OrderStatusTracker
if [ -f "$FLINK_JOBS_DIR/target/OrderStatusTracker.jar" ]; then
    echo "Deploying OrderStatusTracker..."
    docker exec flink-jobmanager flink run /opt/flink/jobs/OrderStatusTracker.jar
    echo -e "${GREEN}✓ OrderStatusTracker deployed${NC}"
    echo ""
else
    echo -e "${YELLOW}⚠ OrderStatusTracker.jar not found${NC}"
fi

# Job 3: RealTimeMetricsAggregator
if [ -f "$FLINK_JOBS_DIR/target/RealTimeMetricsAggregator.jar" ]; then
    echo "Deploying RealTimeMetricsAggregator..."
    docker exec flink-jobmanager flink run /opt/flink/jobs/RealTimeMetricsAggregator.jar
    echo -e "${GREEN}✓ RealTimeMetricsAggregator deployed${NC}"
    echo ""
else
    echo -e "${YELLOW}⚠ RealTimeMetricsAggregator.jar not found${NC}"
fi

# List running jobs
echo "=========================================="
echo "Running Flink Jobs:"
echo "=========================================="
docker exec flink-jobmanager flink list

echo ""
echo "=========================================="
echo -e "${GREEN}✓ Deployment Complete!${NC}"
echo "=========================================="
echo ""
echo "View Flink Dashboard: http://localhost:8081"
echo ""

