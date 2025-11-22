# LensArt Data Pipeline - Implementation Plan & Status

**Tài liệu:** Kế hoạch triển khai và kiểm tra tiến độ  
**Ngày tạo:** 22/11/2024  
**Phiên bản:** 1.0.0

---

## 📊 Tổng quan Tiến độ

| Phase | Mô tả | Trạng thái | Tiến độ |
|-------|-------|-----------|---------|
| **Phase 1** | Docker Infrastructure | ✅ **Hoàn thành** | 100% |
| **Phase 2** | Database Schema | ✅ **Hoàn thành** | 100% |
| **Phase 3** | Laravel Kafka Integration | ✅ **Hoàn thành** | 100% |
| **Phase 4** | Helper Scripts | ✅ **Hoàn thành** | 100% |
| **Phase 5** | **Flink Jobs (Java)** | ❌ **CHƯA LÀM** | 0% |
| **Phase 6** | Integration Testing | ⚠️ **Thiếu** | 20% |
| **Phase 7** | Documentation | ✅ **Hoàn thành** | 90% |

**Tổng tiến độ dự án:** ~65%

---

## ✅ Phần Đã Hoàn Thành

### 1. Docker Infrastructure (100% ✅)

**File:** `data_pipeline/docker/docker-compose.yml`

**Các service đã setup:**
- ✅ Zookeeper (port 2181)
- ✅ Kafka Broker (port 9092)
- ✅ Kafka UI (port 8080)
- ✅ PostgreSQL (port 5432)
- ✅ PgAdmin (port 5050)
- ✅ Flink Job Manager (port 8081)
- ✅ Flink Task Manager
- ✅ Docker volumes cho data persistence
- ✅ Docker network configuration
- ✅ Health checks cho tất cả services

**Tính năng:**
- ✅ Auto-restart services
- ✅ Volume mounts for Flink jobs
- ✅ Proper networking between containers
- ✅ Resource limits configured

---

### 2. Database Schema (100% ✅)

**File:** `data_pipeline/docker/postgres/init.sql`

**Các table đã tạo:**
- ✅ `orders_raw` - Raw events từ Kafka
- ✅ `orders_processed` - Processed order data
- ✅ `order_status_history` - Status change tracking
- ✅ `order_metrics` - Aggregated metrics
- ✅ `order_items_analytics` - Product analytics

**Các index đã tạo:**
- ✅ Primary keys cho tất cả tables
- ✅ Foreign keys với CASCADE delete
- ✅ Indexes cho performance (order_id, branch_id, dates, etc.)
- ✅ GIN index cho JSONB data
- ✅ Composite indexes cho metrics queries

**Views đã tạo:**
- ✅ `v_daily_order_summary` - Daily metrics view
- ✅ `v_branch_performance` - Branch analytics
- ✅ `v_product_popularity` - Product rankings
- ✅ `v_recent_orders` - Recent orders detail

**Functions đã tạo:**
- ✅ `upsert_order_metrics()` - Function để update metrics với UPSERT logic

**Check constraints:**
- ✅ Positive price validation
- ✅ Positive quantity validation
- ✅ Status count validation

---

### 3. Laravel Kafka Integration (100% ✅)

**File:** `app/Http/Controllers/KafkaEventController.php`

**API Endpoints đã implement:**
- ✅ `POST /api/kafka/events/order-created` - Send order created event
- ✅ `POST /api/kafka/events/order-updated` - Send order updated event
- ✅ `POST /api/kafka/events/order-cancelled` - Send order cancelled event
- ✅ `POST /api/kafka/events/order-status-changed` - Send status change event
- ✅ `POST /api/kafka/events/generic` - Send generic event
- ✅ `GET/POST /api/kafka/test-connection` - Test Kafka connection

**Tính năng:**
- ✅ Request validation
- ✅ Error handling với try-catch
- ✅ Logging cho debugging
- ✅ JSON response format
- ✅ OrderEvent class cho data transformation
- ✅ KafkaService integration

---

### 4. Helper Scripts (100% ✅)

**File:** `data_pipeline/scripts/`

**Scripts đã tạo:**
- ✅ `start-all.sh` - Start tất cả Docker services
- ✅ `stop-all.sh` - Stop tất cả services
- ✅ `restart-all.sh` - Restart services
- ✅ `deploy-jobs.sh` - Deploy Flink jobs (placeholder)
- ✅ `reset-data.sh` - Reset database và topics
- ✅ `test-flow.sh` - Test end-to-end flow (placeholder)

**Tính năng:**
- ✅ Service health checks
- ✅ Wait for services to be ready
- ✅ Auto-create Kafka topics
- ✅ Color-coded console output
- ✅ Error handling

---

### 5. Documentation (90% ✅)

**Files đã tạo:**
- ✅ `data_pipeline/README.md` - Main documentation
- ✅ `data_pipeline/QUICK_START.md` - Quick start guide
- ✅ `data_pipeline/COMPLETE_SETUP_GUIDE.md` - Step-by-step setup
- ✅ `KAFKA_FLINK_LOCAL_SETUP.md` - Detailed architecture
- ✅ `KAFKA_SETUP.md` - Kafka integration guide
- ✅ `COMMANDS_REFERENCE.md` - Command reference

**Nội dung:**
- ✅ Architecture diagrams
- ✅ Setup instructions
- ✅ Command references
- ✅ Troubleshooting guides
- ✅ Data flow diagrams
- ✅ API endpoint documentation

---

## ❌ Phần Chưa Hoàn Thành (CRITICAL)

### 5. Flink Jobs - Java Implementation (0% ❌)

**Status:** **CHƯA CÓ MỘT DÒNG CODE NÀO!**

**Thư mục:** `data_pipeline/flink-jobs/` - Hiện tại RỖNG

**Những gì cần làm:**

#### 5.1. Setup Maven Project (URGENT)

**File cần tạo:** `data_pipeline/flink-jobs/pom.xml`

```xml
- Apache Flink dependencies (1.18.0)
- Flink Kafka Connector (3.0.0)
- PostgreSQL JDBC Driver (42.6.0)
- Jackson for JSON (2.15.0)
- Log4j2 for logging
- Maven compiler plugin
- Maven shade plugin (để build fat JAR)
```

**Ước tính thời gian:** 30 phút

---

#### 5.2. Flink Job 1: OrderEventProcessor (HIGH PRIORITY)

**File cần tạo:** `src/main/java/com/lensart/pipeline/jobs/OrderEventProcessor.java`

**Mục đích:**
- Consume events từ 3 Kafka topics: `order-created`, `order-updated`, `order-cancelled`
- Process và validate data
- Write vào PostgreSQL: `orders_raw` và `orders_processed` tables

**Tính năng cần implement:**
```java
1. Source: Kafka Consumer với 3 topics
2. Deserialization: JSON → OrderEvent object
3. Validation:
   - Check required fields
   - Validate data types
   - Validate business rules (price > 0, etc.)
4. Transformation:
   - Enrich data (add metadata)
   - Format dates
   - Calculate derived fields
5. Dual Sink:
   - Sink 1: orders_raw (raw event log)
   - Sink 2: orders_processed (processed data)
6. Error Handling:
   - Dead Letter Queue (DLQ) cho failed events
   - Retry logic
   - Error logging
7. Checkpointing:
   - Enable checkpoints mỗi 60 giây
   - Exactly-once semantics
```

**Classes cần tạo:**
- `OrderEventProcessor.java` - Main job
- `OrderEvent.java` - Event data model
- `OrderEventDeserializer.java` - JSON deserializer
- `PostgresSink.java` - Custom PostgreSQL sink
- `ValidationUtils.java` - Validation helpers

**Ước tính thời gian:** 3-4 giờ

---

#### 5.3. Flink Job 2: OrderStatusTracker (MEDIUM PRIORITY)

**File cần tạo:** `src/main/java/com/lensart/pipeline/jobs/OrderStatusTracker.java`

**Mục đích:**
- Track order status changes over time
- Calculate processing time between statuses
- Write vào `order_status_history` table

**Tính năng cần implement:**
```java
1. Source: Kafka topic "order-events"
2. Filter: Chỉ xử lý events có type = "order.status_changed"
3. Keyed State:
   - Key by order_id
   - Store previous status per order
   - Store previous timestamp
4. Processing:
   - Compare old_status vs new_status
   - Calculate processing_time_seconds
   - Detect anomalies (stuck orders)
5. Sink: PostgreSQL order_status_history
6. Windowing:
   - Session window để group related changes
   - Timeout cho stuck orders (24 hours)
```

**Classes cần tạo:**
- `OrderStatusTracker.java` - Main job
- `OrderStatusEvent.java` - Status event model
- `OrderStatusState.java` - State management
- `StatusHistoryRecord.java` - Output record

**Ước tính thời gian:** 2-3 giờ

---

#### 5.4. Flink Job 3: RealTimeMetricsAggregator (HIGH PRIORITY)

**File cần tạo:** `src/main/java/com/lensart/pipeline/jobs/RealTimeMetricsAggregator.java`

**Mục đích:**
- Calculate real-time metrics per hour per branch
- Aggregate orders, revenue, status counts
- UPSERT vào `order_metrics` table

**Tính năng cần implement:**
```java
1. Source: All Kafka topics (order-created, order-updated, etc.)
2. Keyed by: (date, hour, branch_id)
3. Windowing:
   - Tumbling window: 1 hour
   - Sliding window: mỗi 5 phút update
4. Aggregation:
   - COUNT(orders) - Total orders
   - SUM(total_price) - Total revenue
   - AVG(total_price) - Average order value
   - COUNT by status (pending, processing, completed, cancelled)
   - COUNT by payment method (cash, online)
5. Sink: PostgreSQL order_metrics
6. Update Strategy: UPSERT (gọi function upsert_order_metrics)
7. Late Data Handling:
   - Watermark: 5 minutes
   - Allow late events up to 1 hour
```

**Classes cần tạo:**
- `RealTimeMetricsAggregator.java` - Main job
- `OrderMetrics.java` - Metrics data model
- `MetricsAggregateFunction.java` - Custom aggregation
- `MetricsUpsertSink.java` - UPSERT sink

**Ước tính thời gian:** 3-4 giờ

---

#### 5.5. Supporting Classes (REQUIRED)

**Configuration:**
- `src/main/java/com/lensart/pipeline/config/KafkaConfig.java`
  - Kafka connection settings
  - Consumer/Producer configs
  - Topic names

- `src/main/java/com/lensart/pipeline/config/DatabaseConfig.java`
  - PostgreSQL connection settings
  - Connection pooling (HikariCP)
  - Retry logic

**Models:**
- `src/main/java/com/lensart/pipeline/models/OrderEvent.java`
- `src/main/java/com/lensart/pipeline/models/OrderDetails.java`
- `src/main/java/com/lensart/pipeline/models/OrderMetrics.java`
- `src/main/java/com/lensart/pipeline/models/OrderStatusHistory.java`

**Serializers:**
- `src/main/java/com/lensart/pipeline/serializers/OrderEventDeserializer.java`
- `src/main/java/com/lensart/pipeline/serializers/JsonDeserializationSchema.java`

**Sinks:**
- `src/main/java/com/lensart/pipeline/sinks/PostgresSink.java`
  - Generic PostgreSQL sink
  - Connection pooling
  - Batch inserts
  - Error handling

- `src/main/java/com/lensart/pipeline/sinks/JdbcConnectionPool.java`

**Utils:**
- `src/main/java/com/lensart/pipeline/utils/ValidationUtils.java`
- `src/main/java/com/lensart/pipeline/utils/DateTimeUtils.java`
- `src/main/java/com/lensart/pipeline/utils/JsonUtils.java`

**Ước tính thời gian:** 2-3 giờ

---

#### 5.6. Resources & Configuration

**Files cần tạo:**

1. `src/main/resources/application.properties`
```properties
# Kafka
kafka.bootstrap.servers=kafka:29092
kafka.consumer.group.id=lensart-flink-consumer

# PostgreSQL
postgres.host=postgres
postgres.port=5432
postgres.database=lensart_events
postgres.user=postgres
postgres.password=postgres

# Flink
flink.checkpoint.interval=60000
flink.checkpoint.mode=EXACTLY_ONCE
```

2. `src/main/resources/log4j2.properties`
```properties
# Logging configuration
```

**Ước tính thời gian:** 30 phút

---

### 6. Integration Testing (20% ⚠️)

**Status:** Scripts có template nhưng chưa hoàn chỉnh

**Cần làm:**

#### 6.1. Update `test-flow.sh`
- Generate sample order data
- Send via Laravel API
- Verify in Kafka (consume messages)
- Verify in PostgreSQL (query tables)
- Generate test report

#### 6.2. Create Test Scripts
- `tests/test-kafka-connection.sh` - Test Kafka connectivity
- `tests/test-flink-jobs.sh` - Verify jobs running
- `tests/generate-test-events.py` - Python script để generate bulk events

#### 6.3. Unit Tests cho Flink Jobs
- Unit tests cho từng Flink job
- Mock Kafka sources
- Test data transformations
- Test validation logic

**Ước tính thời gian:** 2-3 giờ

---

## 📋 Kế Hoạch Thực Hiện (Roadmap)

### Week 1: Flink Jobs Foundation (Ưu tiên cao nhất)

#### Day 1-2: Setup & Job 1
- [ ] Tạo `pom.xml` với tất cả dependencies
- [ ] Setup project structure (packages, folders)
- [ ] Tạo models (OrderEvent, OrderDetails)
- [ ] Tạo configuration classes
- [ ] **Implement Job 1: OrderEventProcessor** (80% effort)
- [ ] Build JAR file đầu tiên
- [ ] Test manual deploy

#### Day 3: Job 3 (Metrics)
- [ ] **Implement Job 3: RealTimeMetricsAggregator**
- [ ] Test windowing logic
- [ ] Test UPSERT functionality

#### Day 4: Job 2 (Status Tracker)
- [ ] **Implement Job 2: OrderStatusTracker**
- [ ] Implement state management
- [ ] Test status tracking logic

#### Day 5: Integration & Testing
- [ ] Deploy tất cả 3 jobs
- [ ] End-to-end testing
- [ ] Fix bugs
- [ ] Performance tuning

---

### Week 2: Testing & Polish

#### Day 1-2: Integration Testing
- [ ] Complete test-flow.sh
- [ ] Generate test events script
- [ ] Automated testing script
- [ ] Load testing (100, 1000, 10000 events)

#### Day 3: Monitoring & Observability
- [ ] Add proper logging
- [ ] Verify Flink metrics
- [ ] Setup alerts (optional)
- [ ] Document troubleshooting steps

#### Day 4: Documentation
- [ ] Update all docs với actual implementation
- [ ] Add code comments
- [ ] Create JOBS.md với detailed job docs
- [ ] Update TROUBLESHOOTING.md

#### Day 5: Final Review
- [ ] Code review
- [ ] Performance testing
- [ ] Final bug fixes
- [ ] Prepare demo

---

## 🎯 Ưu Tiên Công Việc (Priority Order)

### P0 - CRITICAL (Phải làm ngay)
1. **Setup pom.xml** - Không có thì không build được
2. **Implement Job 1: OrderEventProcessor** - Core functionality
3. **Implement Job 3: RealTimeMetricsAggregator** - Business value cao
4. **End-to-end testing** - Verify everything works

### P1 - HIGH (Làm trong tuần đầu)
5. **Implement Job 2: OrderStatusTracker** - Monitoring value
6. **Integration testing scripts** - Automation

### P2 - MEDIUM (Làm khi có thời gian)
7. **Performance optimization** - After basic functionality works
8. **Advanced monitoring** - Nice to have
9. **Documentation polish** - Clean up docs

### P3 - LOW (Optional)
10. **Job 4: ProductAnalytics** - Extra analytics (not in core requirements)
11. **Grafana dashboard** - Visualization (future enhancement)
12. **CI/CD pipeline** - Automation (future)

---

## 📊 Chi Tiết Thời Gian Ước Tính

| Task | Thời gian | Độ khó | Ưu tiên |
|------|-----------|--------|---------|
| Setup pom.xml | 30 mins | Easy | P0 |
| Project structure | 30 mins | Easy | P0 |
| Config classes | 1 hour | Medium | P0 |
| Models & serializers | 1.5 hours | Medium | P0 |
| PostgresSink (generic) | 1.5 hours | Hard | P0 |
| Job 1: OrderEventProcessor | 3-4 hours | Hard | P0 |
| Job 3: RealTimeMetricsAggregator | 3-4 hours | Hard | P0 |
| Job 2: OrderStatusTracker | 2-3 hours | Medium | P1 |
| Integration tests | 2-3 hours | Medium | P1 |
| Documentation update | 1-2 hours | Easy | P1 |
| Bug fixes & polish | 2-3 hours | Variable | P1 |
| **TOTAL** | **20-25 hours** | | |

**Realistic timeline:** 1-1.5 tuần làm việc (full-time)

---

## ⚠️ Rủi Ro & Challenges

### Technical Challenges

1. **Flink State Management**
   - Challenge: Quản lý keyed state cho status tracking
   - Solution: Sử dụng ValueState<T> với proper TTL
   - Risk: Medium

2. **PostgreSQL Connection Pooling**
   - Challenge: Avoid connection exhaustion
   - Solution: Implement HikariCP connection pool
   - Risk: Medium

3. **Exactly-Once Semantics**
   - Challenge: Ensure no data loss or duplicates
   - Solution: Enable Flink checkpointing + Kafka transactions
   - Risk: High

4. **Late Event Handling**
   - Challenge: Handle out-of-order events
   - Solution: Watermarks + allowed lateness
   - Risk: Medium

5. **UPSERT Logic**
   - Challenge: Update existing metrics without overwrite
   - Solution: Use PostgreSQL UPSERT function already created
   - Risk: Low

### Time Risks

1. **Learning Curve**
   - Flink API có thể phức tạp nếu chưa quen
   - Mitigation: Follow official docs + examples
   - Buffer: +20% time

2. **Debugging Distributed Systems**
   - Flink + Kafka + PostgreSQL = nhiều moving parts
   - Mitigation: Good logging + monitoring
   - Buffer: +30% time for debugging

3. **Integration Issues**
   - Docker networking, version compatibility
   - Mitigation: Test incrementally
   - Buffer: Already accounted in estimate

---

## ✅ Checklist Trước Khi Hoàn Thành

### Development Checklist
- [ ] All 3 Flink jobs implemented
- [ ] pom.xml configured correctly
- [ ] JAR files build successfully
- [ ] All Java classes have proper error handling
- [ ] Logging added to all critical paths
- [ ] Code comments added
- [ ] No hardcoded values (use config)

### Testing Checklist
- [ ] Docker services start successfully
- [ ] Kafka topics created automatically
- [ ] Database tables initialized
- [ ] Flink jobs deploy without errors
- [ ] Send test event from Laravel → appears in Kafka
- [ ] Flink processes event → writes to PostgreSQL
- [ ] All 5 tables have data after test
- [ ] Metrics calculated correctly
- [ ] Status history tracked correctly
- [ ] Handle 1000+ events without failure

### Documentation Checklist
- [ ] README.md updated with actual steps
- [ ] JOBS.md created with job details
- [ ] TROUBLESHOOTING.md updated
- [ ] Code comments complete
- [ ] API documentation accurate

---

## 📝 Notes & Observations

### What's Working Well
✅ **Infrastructure:** Docker setup is excellent and production-ready  
✅ **Database:** Schema is well-designed with proper indexes and constraints  
✅ **Laravel Integration:** API endpoints are clean and well-structured  
✅ **Documentation:** Very comprehensive setup guides

### What Needs Attention
⚠️ **Flink Jobs:** Core functionality completely missing  
⚠️ **Testing:** Need automated integration tests  
⚠️ **Deployment:** deploy-jobs.sh needs to be completed

### Recommendations

1. **Focus on Flink Jobs First**
   - Infrastructure đã sẵn sàng
   - Chỉ thiếu processing logic
   - Prioritize Job 1 và Job 3

2. **Use Flink Examples**
   - Tham khảo Flink official examples
   - Copy patterns cho Kafka connector
   - Adapt cho use case của LensArt

3. **Test Incrementally**
   - Build Job 1 first
   - Test với 1 event
   - Scale up gradually

4. **Monitor Resource Usage**
   - Flink cần RAM (minimum 2GB per TaskManager)
   - PostgreSQL connection pool limits
   - Kafka throughput

---

## 🚀 Next Steps (Immediate Actions)

### This Week (High Priority)

1. **Create pom.xml** (30 mins)
   ```bash
   cd data_pipeline/flink-jobs
   # Create pom.xml với dependencies
   ```

2. **Setup Project Structure** (30 mins)
   ```bash
   mkdir -p src/main/java/com/lensart/pipeline/{jobs,models,config,sinks,utils}
   mkdir -p src/main/resources
   mkdir -p src/test/java
   ```

3. **Implement OrderEventProcessor** (Day 1-2)
   - Start với simplest version
   - Add features incrementally

4. **Build & Deploy First Job** (Day 2)
   ```bash
   mvn clean package
   ./scripts/deploy-jobs.sh
   ```

5. **Test End-to-End** (Day 2-3)
   ```bash
   ./scripts/test-flow.sh
   # Verify data in PostgreSQL
   ```

---

## 📞 Support Resources

### Official Documentation
- [Flink Documentation](https://nightlies.apache.org/flink/flink-docs-release-1.18/)
- [Flink Kafka Connector](https://nightlies.apache.org/flink/flink-docs-release-1.18/docs/connectors/datastream/kafka/)
- [Flink JDBC Connector](https://nightlies.apache.org/flink/flink-docs-release-1.18/docs/connectors/datastream/jdbc/)

### Example Projects
- [Flink Examples on GitHub](https://github.com/apache/flink/tree/master/flink-examples)
- Search: "Flink Kafka PostgreSQL example"

### Community
- [Flink User Mailing List](https://flink.apache.org/community.html)
- Stack Overflow: tag `apache-flink`

---

## ✨ Kết Luận

### Current State
- **Infrastructure:** ✅ Production-ready
- **Database:** ✅ Well-designed and optimized
- **Laravel API:** ✅ Complete and functional
- **Flink Jobs:** ❌ **CRITICAL GAP - 0% complete**

### What Makes This Urgent
Without Flink jobs, the entire pipeline is **non-functional**:
- Events sent to Kafka but **NOT PROCESSED**
- PostgreSQL tables remain **EMPTY**
- No real-time metrics
- No status tracking
- **Zero business value** from the infrastructure

### Success Criteria
Pipeline hoàn chỉnh khi:
1. ✅ Send event từ Laravel
2. ✅ Event appears in Kafka (verified in Kafka UI)
3. ✅ Flink job processes event (verified in Flink UI)
4. ✅ Data written to PostgreSQL (verified in PgAdmin)
5. ✅ Metrics updated in real-time
6. ✅ Can handle 1000+ events per minute

### Estimated Completion
- **Optimistic:** 1 tuần (full-time, experienced with Flink)
- **Realistic:** 1.5 tuần (learning curve included)
- **Pessimistic:** 2 tuần (with debugging time)

---

**Bạn muốn tôi bắt đầu implement Flink jobs ngay không?**

Tôi có thể bắt đầu với:
1. ✅ Tạo `pom.xml`
2. ✅ Setup project structure
3. ✅ Implement Job 1: OrderEventProcessor

**Let me know when you're ready to proceed!** 🚀

---

**Document Version:** 1.0.0  
**Last Updated:** 22/11/2024  
**Author:** AI Assistant  
**Status:** Ready for Implementation

