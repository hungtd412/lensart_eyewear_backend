#!/bin/bash

# ============================================
# Reset All Data (WARNING: Destructive!)
# ============================================

set -e  # Exit on error

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo "=========================================="
echo -e "${RED}⚠️  WARNING: DATA RESET${NC}"
echo "=========================================="
echo ""
echo -e "${YELLOW}This will DELETE ALL DATA including:${NC}"
echo "  - Kafka messages"
echo "  - PostgreSQL database"
echo "  - Flink checkpoints & savepoints"
echo ""
echo -n "Are you sure you want to continue? (yes/no): "
read -r response

if [ "$response" != "yes" ]; then
    echo ""
    echo "Operation cancelled."
    exit 0
fi

echo ""
echo "=========================================="
echo "Resetting Data..."
echo "=========================================="
echo ""

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DOCKER_DIR="$SCRIPT_DIR/../docker"

# Change to docker directory
cd "$DOCKER_DIR"

# Stop all services
echo "Stopping all services..."
docker-compose down
echo ""

# Remove all volumes
echo "Removing all volumes..."
docker volume rm -f lensart-kafka-data 2>/dev/null || true
docker volume rm -f lensart-postgres-data 2>/dev/null || true
docker volume rm -f lensart-zookeeper-data 2>/dev/null || true
docker volume rm -f lensart-zookeeper-logs 2>/dev/null || true
docker volume rm -f lensart-pgadmin-data 2>/dev/null || true
docker volume rm -f lensart-flink-checkpoints 2>/dev/null || true
docker volume rm -f lensart-flink-savepoints 2>/dev/null || true
echo -e "${GREEN}✓ Volumes removed${NC}"
echo ""

# Remove network
echo "Removing network..."
docker network rm lensart-network 2>/dev/null || true
echo -e "${GREEN}✓ Network removed${NC}"
echo ""

echo "=========================================="
echo -e "${GREEN}✓ Data Reset Complete!${NC}"
echo "=========================================="
echo ""
echo "To start fresh, run:"
echo "  ./scripts/start-all.sh"
echo ""

