#!/bin/bash

# ============================================
# Stop All Data Pipeline Services
# ============================================

set -e  # Exit on error

echo "=========================================="
echo "Stopping LensArt Data Pipeline"
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

# Stop all Flink jobs first (gracefully)
echo "Stopping Flink jobs..."
docker exec flink-jobmanager bash -c "flink list 2>/dev/null | grep -oP '(?<=: )[a-f0-9]{32}' | xargs -I {} flink cancel {}" 2>/dev/null || true
sleep 2
echo -e "${GREEN}✓ Flink jobs stopped${NC}"
echo ""

# Stop all services
echo "Stopping all services..."
docker-compose down

echo ""
echo "=========================================="
echo -e "${GREEN}✓ All Services Stopped${NC}"
echo "=========================================="
echo ""
echo "To start services again, run:"
echo "  ./scripts/start-all.sh"
echo ""
echo "To remove all data (volumes), run:"
echo "  ./scripts/reset-data.sh"
echo ""

