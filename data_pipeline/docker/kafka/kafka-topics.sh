#!/bin/bash

# ============================================
# Kafka Topics Initialization Script
# Purpose: Create all required topics for LensArt Data Pipeline
# ============================================

echo "=========================================="
echo "LensArt Kafka Topics Initialization"
echo "=========================================="

# Wait for Kafka to be ready
echo "Waiting for Kafka to be ready..."
sleep 10

# Kafka broker address
KAFKA_BROKER="localhost:9092"

# Function to create topic
create_topic() {
    local topic_name=$1
    local partitions=$2
    local replication_factor=$3
    local retention_hours=$4
    
    echo "Creating topic: $topic_name"
    
    /usr/bin/kafka-topics --create \
        --bootstrap-server $KAFKA_BROKER \
        --topic $topic_name \
        --partitions $partitions \
        --replication-factor $replication_factor \
        --config retention.ms=$((retention_hours * 3600000)) \
        --if-not-exists
    
    if [ $? -eq 0 ]; then
        echo "✓ Topic '$topic_name' created successfully"
    else
        echo "✗ Failed to create topic '$topic_name'"
    fi
    echo ""
}

# ============================================
# Create Topics
# ============================================

# Topic 1: order-created
# Purpose: Events when new orders are created
create_topic "order-created" 3 1 168  # 7 days retention

# Topic 2: order-updated
# Purpose: Events when orders are updated
create_topic "order-updated" 3 1 168  # 7 days retention

# Topic 3: order-cancelled
# Purpose: Events when orders are cancelled
create_topic "order-cancelled" 3 1 168  # 7 days retention

# Topic 4: order-events
# Purpose: Generic order events (status changes, payment changes)
create_topic "order-events" 3 1 168  # 7 days retention

# Topic 5: order-events-dlq (Dead Letter Queue)
# Purpose: Failed events that need manual intervention
create_topic "order-events-dlq" 1 1 720  # 30 days retention

# ============================================
# List All Topics
# ============================================

echo "=========================================="
echo "Current Kafka Topics:"
echo "=========================================="

/usr/bin/kafka-topics --list --bootstrap-server $KAFKA_BROKER

echo ""
echo "=========================================="
echo "Topic Details:"
echo "=========================================="

# Describe each topic
for topic in "order-created" "order-updated" "order-cancelled" "order-events" "order-events-dlq"
do
    echo "Topic: $topic"
    /usr/bin/kafka-topics --describe --bootstrap-server $KAFKA_BROKER --topic $topic
    echo ""
done

echo "=========================================="
echo "Kafka Topics Initialization Complete!"
echo "=========================================="

