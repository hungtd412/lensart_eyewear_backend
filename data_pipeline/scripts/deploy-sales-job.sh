#!/bin/bash

# ============================================
# Deploy Sales Transaction Flink Job
# ============================================

set -e  # Exit on error

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Deploy Sales Transaction Job${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
FLINK_JOBS_DIR="$SCRIPT_DIR/../flink-jobs"

# Parse arguments
SKIP_BUILD=false
if [ "$1" == "--skip-build" ]; then
    SKIP_BUILD=true
    echo -e "${YELLOW}⚠ Skipping build step${NC}"
fi

# Check if Flink is running
if ! docker ps | grep -q flink-jobmanager; then
    echo -e "${RED}✗ Flink Job Manager is not running${NC}"
    echo ""
    echo "Please start the data pipeline first:"
    echo "  cd data_pipeline"
    echo "  ./scripts/start-all.sh"
    exit 1
fi

echo -e "${GREEN}✓ Flink is running${NC}"

# Check if Maven is installed
if ! command -v mvn &> /dev/null; then
    echo -e "${RED}✗ Maven is not installed${NC}"
    echo ""
    echo "Please install Maven:"
    echo "  - Windows: https://maven.apache.org/download.cgi"
    echo "  - macOS: brew install maven"
    echo "  - Linux: sudo apt install maven"
    exit 1
fi

echo -e "${GREEN}✓ Maven is installed${NC}"
echo ""

# Build Flink job
if [ "$SKIP_BUILD" = false ]; then
    cd "$FLINK_JOBS_DIR"
    
    if [ ! -f "pom.xml" ]; then
        echo -e "${RED}✗ pom.xml not found in $FLINK_JOBS_DIR${NC}"
        echo ""
        echo "Please ensure the Flink jobs are properly set up."
        exit 1
    fi
    
    echo -e "${BLUE}Building Flink job...${NC}"
    mvn clean package -DskipTests
    
    if [ $? -ne 0 ]; then
        echo -e "${RED}✗ Maven build failed!${NC}"
        exit 1
    fi
    
    echo -e "${GREEN}✓ Build successful${NC}"
    echo ""
fi

# Find JAR file
cd "$FLINK_JOBS_DIR"
JAR_FILE=$(find target -name "lensart-sales-pipeline-*.jar" -not -name "*-original.jar" -type f | head -n 1)

if [ -z "$JAR_FILE" ]; then
    echo -e "${RED}✗ JAR file not found in target directory${NC}"
    echo ""
    echo "Expected: target/lensart-sales-pipeline-*.jar"
    echo "Please ensure Maven build completed successfully."
    exit 1
fi

JAR_FILENAME=$(basename "$JAR_FILE")
echo -e "${GREEN}✓ Found JAR: $JAR_FILENAME${NC}"
echo "  Size: $(du -h "$JAR_FILE" | cut -f1)"
echo ""

# Copy JAR to Flink container
echo -e "${BLUE}Copying JAR to Flink JobManager...${NC}"
docker cp "$JAR_FILE" flink-jobmanager:/opt/flink/usrlib/"$JAR_FILENAME"

if [ $? -ne 0 ]; then
    echo -e "${RED}✗ Failed to copy JAR to Flink container${NC}"
    exit 1
fi

echo -e "${GREEN}✓ JAR copied to Flink container${NC}"
echo ""

# Check for running jobs
echo -e "${BLUE}Checking for existing jobs...${NC}"
RUNNING_JOBS=$(docker exec flink-jobmanager flink list -r 2>/dev/null || echo "")

if echo "$RUNNING_JOBS" | grep -q "Sales Transaction"; then
    echo -e "${YELLOW}⚠ Sales Transaction job is already running${NC}"
    echo ""
    docker exec flink-jobmanager flink list
    echo ""
    
    read -p "Stop existing job and redeploy? (y/N): " -n 1 -r
    echo ""
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        JOB_ID=$(echo "$RUNNING_JOBS" | grep "Sales Transaction" | awk '{print $4}')
        if [ -n "$JOB_ID" ]; then
            echo -e "${BLUE}Canceling job: $JOB_ID${NC}"
            docker exec flink-jobmanager flink cancel "$JOB_ID"
            echo -e "${GREEN}✓ Job canceled${NC}"
            sleep 5
        fi
    else
        echo -e "${YELLOW}Deployment canceled by user${NC}"
        exit 0
    fi
fi

# Submit job to Flink
echo -e "${BLUE}Submitting job to Flink cluster...${NC}"
echo ""

docker exec flink-jobmanager flink run \
    -d \
    -c com.lensart.pipeline.SalesTransactionJob \
    /opt/flink/usrlib/"$JAR_FILENAME"

if [ $? -ne 0 ]; then
    echo -e "${RED}✗ Failed to submit job to Flink${NC}"
    exit 1
fi

echo ""
echo -e "${GREEN}✓ Job submitted successfully!${NC}"
echo ""

# Wait for job to start
echo "Waiting for job to start..."
sleep 5

# Check job status
echo -e "${BLUE}Current running jobs:${NC}"
docker exec flink-jobmanager flink list

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Deployment Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${CYAN}📊 Next Steps:${NC}"
echo ""
echo -e "  1. ${BLUE}Monitor Flink Dashboard:${NC}"
echo -e "     http://localhost:8081"
echo ""
echo -e "  2. ${BLUE}Send test transactions:${NC}"
echo -e "     curl -X POST http://localhost:8000/api/kafka/transactions/sales \\"
echo -e "       -H \"Authorization: Bearer YOUR_TOKEN\" \\"
echo -e "       -H \"Content-Type: application/json\" \\"
echo -e "       -d '{\"order_id\": 1}'"
echo ""
echo -e "  3. ${BLUE}Check Kafka UI:${NC}"
echo -e "     http://localhost:8080"
echo ""
echo -e "  4. ${BLUE}Verify PostgreSQL data:${NC}"
echo -e "     docker exec -it postgres psql -U postgres -d lensart_events \\"
echo -e "       -c \"SELECT * FROM sales_transactions ORDER BY created_at DESC LIMIT 5;\""
echo ""
echo -e "${CYAN}🛠️  Useful Commands:${NC}"
echo ""
echo -e "  ${YELLOW}# List running jobs${NC}"
echo -e "  docker exec flink-jobmanager flink list"
echo ""
echo -e "  ${YELLOW}# View job logs${NC}"
echo -e "  docker logs flink-jobmanager -f"
echo -e "  docker logs flink-taskmanager -f"
echo ""
echo -e "  ${YELLOW}# Cancel a job${NC}"
echo -e "  docker exec flink-jobmanager flink cancel <JOB_ID>"
echo ""
echo -e "  ${YELLOW}# Redeploy (quick)${NC}"
echo -e "  ./deploy-sales-job.sh --skip-build"
echo ""

