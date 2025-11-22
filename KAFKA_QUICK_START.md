# Kafka Quick Start Guide - BƯỚC 1

## 🚀 Nhanh chóng bắt đầu

### 1. Cấu hình .env

Thêm vào file `.env`:

```env
KAFKA_BROKERS=localhost:9092
KAFKA_ORDER_TOPIC=order-events
KAFKA_ORDER_CREATED_TOPIC=order-created
KAFKA_ORDER_UPDATED_TOPIC=order-updated
KAFKA_ORDER_CANCELLED_TOPIC=order-cancelled
KAFKA_PRODUCER_TIMEOUT=10000
KAFKA_PRODUCER_ASYNC=true
KAFKA_REQUIRED_ACK=1
KAFKA_CONSUMER_GROUP=lensart-consumer-group
KAFKA_SASL_ENABLE=false
KAFKA_SSL_ENABLE=false
```

### 2. Clear cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Test API

```bash
# Test connection (cần Bearer Token)
curl -X GET http://localhost:8000/api/kafka/test-connection \
  -H "Authorization: Bearer YOUR_TOKEN"

# Send order created event
curl -X POST http://localhost:8000/api/kafka/events/order-created \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"order_id": 1}'
```

## 📝 API Endpoints Summary

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| GET | `/api/kafka/test-connection` | Test kết nối Kafka |
| POST | `/api/kafka/events/order-created` | Gửi event đơn hàng được tạo |
| POST | `/api/kafka/events/order-updated` | Gửi event đơn hàng được cập nhật |
| POST | `/api/kafka/events/order-cancelled` | Gửi event đơn hàng bị hủy |
| POST | `/api/kafka/events/order-status-changed` | Gửi event thay đổi trạng thái |
| POST | `/api/kafka/events/send` | Gửi event tùy chỉnh |

## 📦 Files được tạo

```
✅ config/kafka.php                           # Cấu hình Kafka
✅ app/Services/KafkaService.php              # Service xử lý Kafka
✅ app/Events/OrderEvent.php                  # Order Event class
✅ app/Http/Controllers/KafkaEventController.php  # API Controller
✅ routes/kafka.api.php                       # API Routes
✅ bootstrap/app.php                          # Đã đăng ký routes
✅ KAFKA_SETUP.md                             # Documentation chi tiết
✅ KAFKA_QUICK_START.md                       # Quick start guide
```

## 🔧 Composer Package

```json
"nmred/kafka-php": "^0.1.6"
```

## ⚙️ Azure Event Hubs (Production)

Cho production trên Azure, cập nhật `.env`:

```env
KAFKA_BROKERS=your-namespace.servicebus.windows.net:9093
KAFKA_SASL_ENABLE=true
KAFKA_SASL_MECHANISM=PLAIN
KAFKA_SASL_USERNAME=$ConnectionString
KAFKA_SASL_PASSWORD=Endpoint=sb://your-namespace.servicebus.windows.net/;SharedAccessKeyName=RootManageSharedAccessKey;SharedAccessKey=YOUR_KEY
KAFKA_SSL_ENABLE=true
```

## 📊 Event Structure Example

```json
{
    "event_type": "order.created",
    "event_id": "evt_unique_id",
    "timestamp": "2024-11-17T10:30:00+07:00",
    "data": {
        "id": 1,
        "user_id": 123,
        "total_price": 1500000,
        "order_status": "Đang xử lý",
        "payment_status": "Đã thanh toán",
        ...
    }
}
```

## ✅ Hoàn thành BƯỚC 1

Tất cả các yêu cầu của BƯỚC 1 đã được hoàn thành:
- [x] Cài đặt Kafka library
- [x] Cấu hình Kafka
- [x] Tạo Service và Event classes
- [x] Tạo API endpoints để bắn events
- [x] Documentation

---

Xem chi tiết tại: `KAFKA_SETUP.md`

