# 🚀 QUICK START - Azure Queue Integration

## 📋 TÓM TẮT: Bạn cần gì?

### 1️⃣ Azure Storage Connection String
```
Lấy từ: Azure Portal → Storage Account → Access Keys → Connection string
```

### 2️⃣ Add vào .env
```ini
QUEUE_CONNECTION=azure-queue
AZURE_STORAGE_CONNECTION_STRING="DefaultEndpointsProtocol=https;AccountName=lensartstorage;AccountKey=...;EndpointSuffix=core.windows.net"
AZURE_STORAGE_QUEUE_NAME=kafka-messages
```

### 3️⃣ Test
```bash
php artisan tinker

\App\Jobs\SendToKafkaQueue::dispatch([
    'order_id' => 999,
    'timestamp' => now()->toIso8601String(),
    'customer_id' => 1,
    'products' => [
        ['product_id' => 1, 'quantity' => 2, 'price' => '100.00']
    ]
]);
```

### 4️⃣ Verify trên Azure
```
Azure Portal → Storage Account → Queues → kafka-messages
→ Check message count > 0
```

---

## 📁 Files đã tạo

| File | Mục đích |
|------|----------|
| `app/Jobs/SendToKafkaQueue.php` | Job push message vào Azure Queue |
| `config/queue.php` | Config Azure Queue connection |
| `AZURE_QUEUE_CONFIG.md` | Hướng dẫn chi tiết |
| `AZURE_SETUP_CHECKLIST.md` | Checklist step-by-step |
| `QUICK_START.md` | File này - tóm tắt nhanh |

---

## 🔄 Flow hoạt động

```
OrderService → SendToKafkaQueue Job → Azure Queue → Azure Function → Ngrok → Kafka → Flink
```

---

## ✅ Done!

**OrderService đã được update sẵn rồi.**

Code bạn cần chỉ là:
1. Lấy Connection String từ Azure
2. Paste vào `.env`
3. Test!

**Chi tiết xem:** `AZURE_SETUP_CHECKLIST.md`

