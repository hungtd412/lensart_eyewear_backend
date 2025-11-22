# Kafka Integration Setup Guide

## Bước 1 - Tạo API bắn event trong Laravel (trên Azure)

### 📋 Tổng quan

Dự án LensArt Eyewear đã được tích hợp với Apache Kafka để xử lý các events liên quan đến đơn hàng (orders). API này cho phép gửi các events về trạng thái đơn hàng đến Kafka message broker.

### 🔧 Cài đặt

#### 1. Dependencies đã được cài đặt:
- `nmred/kafka-php` - Pure PHP Kafka client library

#### 2. Cấu hình Environment Variables

Thêm các biến sau vào file `.env`:

```env
# Kafka Configuration
KAFKA_BROKERS=localhost:9092
# Hoặc nếu sử dụng Azure Event Hubs:
# KAFKA_BROKERS=your-eventhub-namespace.servicebus.windows.net:9093

# Kafka Topics
KAFKA_ORDER_TOPIC=order-events
KAFKA_ORDER_CREATED_TOPIC=order-created
KAFKA_ORDER_UPDATED_TOPIC=order-updated
KAFKA_ORDER_CANCELLED_TOPIC=order-cancelled

# Kafka Producer Configuration
KAFKA_PRODUCER_TIMEOUT=10000
KAFKA_PRODUCER_ASYNC=true
KAFKA_REQUIRED_ACK=1

# Kafka Consumer Configuration
KAFKA_CONSUMER_GROUP=lensart-consumer-group
KAFKA_CONSUMER_TIMEOUT=10000

# Kafka Security (SASL/SSL) - Cho Azure Event Hubs
KAFKA_SASL_ENABLE=false
KAFKA_SASL_MECHANISM=PLAIN
KAFKA_SASL_USERNAME=
KAFKA_SASL_PASSWORD=

KAFKA_SSL_ENABLE=false
KAFKA_SSL_CA_CERT=
KAFKA_SSL_CERT=
KAFKA_SSL_KEY=
```

### 📁 Cấu trúc Files đã tạo:

```
app/
├── Events/
│   └── OrderEvent.php                    # Event class cho order events
├── Services/
│   └── KafkaService.php                  # Service xử lý Kafka operations
└── Http/
    └── Controllers/
        └── KafkaEventController.php      # Controller cho Kafka API endpoints

config/
└── kafka.php                             # Kafka configuration

routes/
└── kafka.api.php                         # API routes cho Kafka events
```

### 🚀 API Endpoints

Tất cả endpoints yêu cầu authentication (`auth:sanctum`) và quyền admin/manager (`can:is-admin-manager`).

Base URL: `http://your-domain.com/api/kafka`

#### 1. Test Kafka Connection
```
GET /api/kafka/test-connection
```

**Response:**
```json
{
    "status": "success",
    "message": "Kafka connection test successful",
    "kafka_brokers": "localhost:9092"
}
```

#### 2. Send Order Created Event
```
POST /api/kafka/events/order-created
```

**Request Body:**
```json
{
    "order_id": 1
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Order created event sent to Kafka successfully",
    "event_type": "order.created",
    "order_id": 1
}
```

#### 3. Send Order Updated Event
```
POST /api/kafka/events/order-updated
```

**Request Body:**
```json
{
    "order_id": 1
}
```

#### 4. Send Order Cancelled Event
```
POST /api/kafka/events/order-cancelled
```

**Request Body:**
```json
{
    "order_id": 1
}
```

#### 5. Send Order Status Changed Event
```
POST /api/kafka/events/order-status-changed
```

**Request Body:**
```json
{
    "order_id": 1,
    "old_status": "Đang xử lý",
    "new_status": "Đang giao hàng"
}
```

#### 6. Send Generic Event
```
POST /api/kafka/events/send
```

**Request Body:**
```json
{
    "event_type": "custom.event",
    "data": {
        "key1": "value1",
        "key2": "value2"
    },
    "topic": "custom-topic" // optional
}
```

### 📦 Event Payload Structure

Khi gửi event, dữ liệu sẽ có cấu trúc như sau:

```json
{
    "event_type": "order.created",
    "event_id": "evt_6556b7c8e9f2a1.23456789",
    "timestamp": "2024-11-17T10:30:00+07:00",
    "data": {
        "id": 1,
        "user_id": 123,
        "branch_id": 1,
        "date": "2024-11-17 10:30:00",
        "address": "123 Nguyen Van Cu, Q5, TP.HCM",
        "note": "Giao hàng buổi chiều",
        "coupon_id": null,
        "total_price": 1500000,
        "order_status": "Đang xử lý",
        "payment_status": "Đã thanh toán",
        "payment_method": "payos",
        "status": true,
        "user": {
            "id": 123,
            "name": "Nguyen Van A",
            "email": "nguyenvana@example.com"
        },
        "branch": {
            "id": 1,
            "name": "LensArt Q1",
            "address": "100 Le Loi, Q1, TP.HCM"
        },
        "order_details": [
            {
                "id": 1,
                "product_id": 10,
                "product_name": "Gọng kính Rayban Classic",
                "color": "Đen",
                "quantity": 1,
                "total_price": 1500000
            }
        ],
        "metadata": {}
    }
}
```

### 🔐 Azure Event Hubs Configuration

Nếu sử dụng Azure Event Hubs (tương thích với Kafka):

1. **Tạo Event Hubs Namespace trên Azure Portal**

2. **Cấu hình Connection String:**
```env
KAFKA_BROKERS=your-namespace.servicebus.windows.net:9093
KAFKA_SASL_ENABLE=true
KAFKA_SASL_MECHANISM=PLAIN
KAFKA_SASL_USERNAME=$ConnectionString
KAFKA_SASL_PASSWORD=Endpoint=sb://your-namespace.servicebus.windows.net/;SharedAccessKeyName=RootManageSharedAccessKey;SharedAccessKey=your-key
KAFKA_SSL_ENABLE=true
```

3. **Tạo Event Hubs (Topics):**
- `order-events`
- `order-created`
- `order-updated`
- `order-cancelled`

### 🧪 Testing

#### Sử dụng Postman hoặc cURL:

```bash
# Test connection
curl -X GET http://localhost:8000/api/kafka/test-connection \
  -H "Authorization: Bearer YOUR_TOKEN"

# Send order created event
curl -X POST http://localhost:8000/api/kafka/events/order-created \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"order_id": 1}'
```

### 📊 Monitoring & Logging

Tất cả events được log tại `storage/logs/laravel.log`:

- Thành công: `Event sent to Kafka topic: {topic}`
- Thất bại: `Failed to send event to Kafka: {error}`

### 🔄 Tích hợp tự động với Order Service

Để tự động gửi events khi tạo/cập nhật/hủy đơn hàng, bạn có thể thêm vào `OrderService.php`:

```php
use App\Services\KafkaService;
use App\Events\OrderEvent;

public function __construct(..., KafkaService $kafkaService) {
    // ...
    $this->kafkaService = $kafkaService;
}

public function store($data) {
    // ... existing code ...
    $order = $this->orderRepository->store($data);
    
    // Send Kafka event
    try {
        $orderEvent = new OrderEvent($order, 'order.created');
        $this->kafkaService->sendOrderCreatedEvent($orderEvent->toKafkaPayload());
    } catch (\Exception $e) {
        \Log::error('Failed to send Kafka event: ' . $e->getMessage());
    }
    
    return response()->json([...]);
}
```

### ⚠️ Lưu ý quan trọng

1. **Cài đặt Kafka/Event Hubs:** Đảm bảo Kafka broker hoặc Azure Event Hubs đã được cấu hình và chạy
2. **Network:** Kiểm tra firewall và network rules cho phép kết nối đến Kafka broker
3. **Authentication:** Sử dụng proper authentication tokens khi gọi API
4. **Error Handling:** Events failed sẽ được log, cần có monitoring để theo dõi
5. **Performance:** Với high volume, xem xét sử dụng async/queue processing

### 📞 Support

Nếu có vấn đề, kiểm tra:
1. Logs tại `storage/logs/laravel.log`
2. Kafka broker logs
3. Network connectivity: `telnet your-kafka-broker 9092`

### ✅ Checklist Hoàn thành Bước 1

- [x] Cài đặt Kafka PHP client library
- [x] Tạo Kafka configuration file
- [x] Tạo KafkaService để publish events
- [x] Tạo OrderEvent class cho event structure
- [x] Tạo KafkaEventController với các API endpoints
- [x] Tạo routes cho Kafka APIs
- [x] Đăng ký routes trong bootstrap/app.php
- [x] Tạo documentation

---

**Version:** 1.0.0  
**Date:** 2024-11-17  
**Author:** LensArt Development Team

