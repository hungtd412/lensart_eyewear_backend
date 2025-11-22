#!/bin/bash

# ============================================
# Restart All Data Pipeline Services
# ============================================

set -e  # Exit on error

echo "=========================================="
echo "Restarting LensArt Data Pipeline"
echo "=========================================="
echo ""

# Get script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Stop all services
echo "Step 1: Stopping all services..."
"$SCRIPT_DIR/stop-all.sh"

echo ""
echo "Waiting 5 seconds..."
sleep 5
echo ""

# Start all services
echo "Step 2: Starting all services..."
"$SCRIPT_DIR/start-all.sh"

echo ""
echo "=========================================="
echo "✓ Restart Complete!"
echo "=========================================="

