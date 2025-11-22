# Windows PowerShell Commands Reference

**For Windows users - All commands work in PowerShell**

---

## 📑 Menu - Table of Contents

### 🎯 Quick Links
- [Quick Reference Card](#-quick-reference-card-windows) - Lệnh nhanh cho daily operations
- [Deploy Jobs](#deploy-jobs-windows) - Triển khai Flink jobs 
- [Common Issues](#-common-issues-on-windows) - Troubleshooting lỗi thường gặp

### 📚 Full Documentation

1. **[🔧 Cancel All Flink Jobs](#-cancel-all-flink-jobs-windows)**
   - Hủy tất cả Flink jobs (manual & automatic)

2. **[🚀 Complete Windows Setup Commands](#-complete-windows-setup-commands)**
   - [Apply Final Schema](#apply-final-schema) - Tạo database schema
   - [Rebuild Flink Jobs](#rebuild-flink-jobs) - Build JAR với Maven
   - [Deploy Jobs](#deploy-jobs-windows) - Deploy Flink jobs (3 methods)
   - [Verify Jobs Running](#verify-jobs-running) - Kiểm tra job status

3. **[🧪 Testing Commands](#-testing-commands-windows)**
   - [Send Test Transactions](#send-test-transactions) - Gửi test data vào Kafka
   - [Check Database](#check-database-windows) - Query PostgreSQL results

4. **[🔄 Daily Operations](#-daily-operations-windows)**
   - [Start Everything](#start-everything) - Khởi động services
   - [Stop Everything](#stop-everything) - Dừng services
   - [Restart Just Flink Jobs](#restart-just-flink-jobs) - Restart jobs only

5. **[📊 Monitoring Commands](#-monitoring-commands-windows)**
   - [Check Everything](#check-everything) - Kiểm tra containers, jobs, database
   - [View Logs](#view-logs) - Xem logs của từng service

6. **[🐛 Troubleshooting](#-troubleshooting-windows)**
   - [Reset Everything](#reset-everything) - Clear data và restart
   - [Check Kafka Messages](#check-kafka-messages) - Debug Kafka topics

7. **[📋 Quick Reference Card](#-quick-reference-card-windows)**
   - Tất cả lệnh quan trọng trong một chỗ

8. **[⚠️ Important Notes for Windows](#️-important-notes-for-windows)**
   - Lưu ý về PowerShell và Windows paths

9. **[🎯 Common Issues on Windows](#-common-issues-on-windows)**
   - [Directory not found in container](#issue-directory-not-found-in-container) ⭐ **MỚI - Phải tạo thư mục trước**
   - [Docker tar writer error](#issue-docker-tar-writer-error--path-resolution-error) 
   - [Command not recognized](#issue-command-not-recognized)
   - [Path not found](#issue-path-not-found)
   - [Long commands](#issue-long-commands)

---

## 💡 Workflow Khuyến Nghị (Recommended Workflow)

### 🚀 Lần đầu setup (First Time Setup)
```
1. Start Everything (🔄 Daily Operations)
2. Apply Final Schema (🚀 Complete Setup)
3. Build Flink Jobs (🚀 Complete Setup)
4. Deploy Jobs (🚀 Complete Setup) - Dùng Method 1 với Resolve-Path
5. Verify Jobs Running (🚀 Complete Setup)
6. Send Test Transactions (🧪 Testing)
7. Check Database (🧪 Testing)
```

### 🔄 Hàng ngày (Daily Usage)
```
1. Start Everything → Wait 60s → Check Status
2. Send transactions (từ Laravel API)
3. Monitor logs & database
4. Stop Everything khi xong việc
```

### 🐛 Khi gặp lỗi (When Errors Occur)
```
1. Check logs của service bị lỗi (📊 Monitoring)
2. Tìm lỗi trong Common Issues (🎯 Common Issues)
3. Thử Reset Everything (🐛 Troubleshooting)
4. Rebuild & Redeploy nếu cần
```

---

## 🔧 Cancel All Flink Jobs (Windows)

### Method 1: Manual (Recommended for Windows)

```powershell
# 1. Liệt kê tất cả jobs đang chạy (List all running jobs)
docker exec flink-jobmanager flink list -r

# 2. Copy các Job IDs từ output (Copy Job IDs from output)

# 3. Hủy từng job một cách thủ công (Cancel each job manually)
docker exec flink-jobmanager flink cancel <JOB_ID_1>
docker exec flink-jobmanager flink cancel <JOB_ID_2>
```

**Giải thích chi tiết:**
- `docker exec`: Thực thi lệnh bên trong container đang chạy
- `flink-jobmanager`: Tên container quản lý các Flink jobs
- `flink list -r`: List tất cả jobs đang running (-r = running only)
- `flink cancel <JOB_ID>`: Hủy job với ID cụ thể (graceful shutdown)
- Thay `<JOB_ID_1>` bằng ID thực tế từ output của lệnh list

---

### Method 2: PowerShell Script (Tự động hủy tất cả)

```powershell
# Lấy danh sách Job IDs và hủy tự động
# Get job IDs automatically and cancel them
$jobs = docker exec flink-jobmanager flink list -r | Select-String ":\s+(\w+)\s+:" | ForEach-Object { $_.Matches.Groups[1].Value }

foreach ($job in $jobs) {
    Write-Host "Canceling job: $job"
    docker exec flink-jobmanager flink cancel $job
}
```

**Giải thích chi tiết:**
- `docker exec ... flink list -r`: Lấy danh sách jobs đang chạy
- `Select-String ":\s+(\w+)\s+:"`: Tìm pattern chứa Job ID (regex matching)
  - `\s+`: Một hoặc nhiều khoảng trắng
  - `(\w+)`: Capture group - chữ và số (Job ID)
- `ForEach-Object { $_.Matches.Groups[1].Value }`: Lấy giá trị Job ID từ regex group
- `foreach ($job in $jobs)`: Loop qua từng Job ID
- `Write-Host`: In thông báo ra console
- `flink cancel $job`: Hủy job với ID đã lấy được

---

## 🚀 Complete Windows Setup Commands

### Apply Final Schema

```powershell
# Bước 1: Copy file schema vào container PostgreSQL
# Step 1: Copy schema file into PostgreSQL container
docker cp data_pipeline\docker\postgres\final_schema.sql postgres:/tmp/

# Bước 2: Thực thi file SQL để tạo/cập nhật database schema
# Step 2: Execute SQL file to create/update database schema
docker exec postgres psql -U postgres -d lensart_events -f /tmp/final_schema.sql
```

**Giải thích chi tiết từng câu lệnh:**

**Lệnh 1:** `docker cp data_pipeline\docker\postgres\final_schema.sql postgres:/tmp/`
- `docker cp`: Copy file từ host vào container (hoặc ngược lại)
- `data_pipeline\docker\postgres\final_schema.sql`: File SQL chứa schema (tables, indexes, views)
- `postgres:/tmp/`: Container tên "postgres", thư mục đích "/tmp/"
- **Mục đích**: Đưa file schema vào container để PostgreSQL có thể đọc

**Lệnh 2:** `docker exec postgres psql -U postgres -d lensart_events -f /tmp/final_schema.sql`
- `docker exec postgres`: Chạy lệnh trong container "postgres"
- `psql`: PostgreSQL command-line client
- `-U postgres`: User name là "postgres" (admin user)
- `-d lensart_events`: Database name là "lensart_events"
- `-f /tmp/final_schema.sql`: File (file) SQL cần thực thi
- **Mục đích**: Chạy các SQL commands để tạo bảng, indexes, views trong database

---

### Rebuild Flink Jobs

```powershell
# Bước 1: Di chuyển vào thư mục flink-jobs
# Step 1: Navigate to flink-jobs directory
cd data_pipeline\flink-jobs

# Bước 2: Build project với Maven
# Step 2: Build project with Maven
mvn clean package -DskipTests
```

**Giải thích chi tiết:**

**Lệnh:** `mvn clean package -DskipTests`
- `mvn`: Maven command-line tool (build tool cho Java projects)
- `clean`: Maven lifecycle phase - xóa thư mục `target/` cũ
  - Đảm bảo build từ đầu, không có file cũ còn sót lại
- `package`: Maven lifecycle phase - compile và đóng gói thành JAR file
  - Tạo file `.jar` trong thư mục `target/`
- `-DskipTests`: Maven option - bỏ qua việc chạy unit tests
  - Tiết kiệm thời gian khi build
  - Sử dụng khi bạn chắc chắn code đã đúng

**Output mong đợi:**
- File JAR sẽ được tạo tại: `data_pipeline\flink-jobs\target\lensart-sales-pipeline-1.0.0.jar`
- Maven sẽ tải các dependencies cần thiết (Flink, Kafka, PostgreSQL drivers)
- Build thành công khi thấy: `BUILD SUCCESS`

**Khi nào cần rebuild:**
- Sau khi sửa code Java của Flink jobs
- Sau khi thay đổi dependencies trong `pom.xml`
- Khi cần version JAR mới để deploy

---

### Deploy Jobs (Windows)

#### Method 1: Recommended - Direct Path (Khuyến nghị)

```powershell
# Bước 1: Tạo thư mục usrlib trong container (nếu chưa có)
# Step 1: Create usrlib directory in container (if not exists)
docker exec flink-jobmanager mkdir -p /opt/flink/usrlib

# Bước 2: Set đường dẫn tuyệt đối của file JAR
# Step 2: Set absolute path of JAR file
$jarPath = "D:\UIT\HK5\WebDevelopment\MyProject\LensArtEyewear\lensart_eyewear_backend\data_pipeline\flink-jobs\target\lensart-sales-pipeline-1.0.0.jar"

# Bước 3: Copy file JAR vào container Flink
# Step 3: Copy JAR file into Flink container
docker cp $jarPath flink-jobmanager:/opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar

# Triển khai Job 1: Xử lý giao dịch bán hàng (Sales Transaction Processor)
# Deploy Job 1: Sales Transaction Processor
# - Đọc events từ Kafka topic "order-created"
# - Chuyển đổi và lưu vào bảng sales_transactions trong PostgreSQL
docker exec flink-jobmanager flink run -d -c com.lensart.pipeline.SalesTransactionJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar

# Triển khai Job 2: Tổng hợp doanh số sản phẩm (Product Sales Aggregator)
# Deploy Job 2: Product Sales Aggregator
# - Tính tổng doanh số theo sản phẩm mỗi 5 phút
# - Lưu kết quả vào bảng product_sales
docker exec flink-jobmanager bash -c "export WINDOW_SIZE_MINUTES=5 && flink run -d -c com.lensart.pipeline.ProductSalesAggregatorJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar"
```

**Giải thích chi tiết các tham số:**
- `mkdir -p /opt/flink/usrlib`: Tạo thư mục (nếu chưa có), `-p` không báo lỗi nếu đã tồn tại
- `$jarPath`: Đường dẫn tuyệt đối của file JAR (thay đổi theo workspace của bạn)
- `docker cp`: Copy file từ host vào container
- `-d`: Chạy job ở chế độ detached (chạy nền)
- `-c`: Chỉ định class chính (main class) của job
- `WINDOW_SIZE_MINUTES=5`: Thiết lập cửa sổ thời gian 5 phút cho aggregation

---

#### Method 2: Alternative - Navigate First (Đảm bảo 100% không lỗi path)

```powershell
# Bước 1: Tạo thư mục trong container
# Step 1: Create directory in container
docker exec flink-jobmanager mkdir -p /opt/flink/usrlib

# Bước 2: Di chuyển đến thư mục chứa JAR file
# Step 2: Navigate to the directory containing JAR file
cd data_pipeline\flink-jobs\target

# Bước 3: Copy file JAR (không cần path phức tạp)
# Step 3: Copy JAR file (no complex path needed)
docker cp lensart-sales-pipeline-1.0.0.jar flink-jobmanager:/opt/flink/usrlib/

# Bước 4: Quay lại thư mục gốc
# Step 4: Return to root directory
cd ..\..\..

# Bước 5: Triển khai cả 2 jobs
# Step 5: Deploy both jobs
docker exec flink-jobmanager flink run -d -c com.lensart.pipeline.SalesTransactionJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar
docker exec flink-jobmanager bash -c "export WINDOW_SIZE_SECONDS=2 && flink run -d -c com.lensart.pipeline.ProductSalesAggregatorJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar"
```

**Ý nghĩa từng câu lệnh:**
1. `cd data_pipeline\flink-jobs\target`: Di chuyển vào thư mục chứa file JAR đã build
2. `docker cp`: Copy file từ máy host vào container Docker
3. `cd ..\..\..\`: Quay lại thư mục gốc của project (3 cấp lên)
4. `docker exec`: Thực thi lệnh bên trong container Docker
5. `flink run -d`: Chạy Flink job ở chế độ nền (detached mode)

---

#### Method 3: Single Command - One Liner (Chạy tất cả trong 1 dòng)

```powershell
# Tạo thư mục, copy JAR và deploy cả 2 jobs trong 1 lệnh
docker exec flink-jobmanager mkdir -p /opt/flink/usrlib; docker cp "D:\UIT\HK5\WebDevelopment\MyProject\LensArtEyewear\lensart_eyewear_backend\data_pipeline\flink-jobs\target\lensart-sales-pipeline-1.0.0.jar" flink-jobmanager:/opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar; docker exec flink-jobmanager flink run -d -c com.lensart.pipeline.SalesTransactionJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar; docker exec flink-jobmanager bash -c "export WINDOW_SIZE_MINUTES=5 && flink run -d -c com.lensart.pipeline.ProductSalesAggregatorJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar"
```

**Lưu ý quan trọng:**
- Thay đường dẫn JAR file theo workspace của bạn
- Dấu `;` ngăn cách các lệnh trong PowerShell
- Nếu gặp lỗi path, hãy dùng Method 2 (navigate first)

---

### Verify Jobs Running

```powershell
# Kiểm tra các Flink jobs đang chạy
# Check running Flink jobs
docker exec flink-jobmanager flink list -r
```

**Giải thích:**
- `flink list`: Liệt kê tất cả Flink jobs
- `-r`: Chỉ hiển thị jobs đang RUNNING
- Output sẽ hiển thị:
  - Job ID (UUID)
  - Job Name (SalesTransactionJob, ProductSalesAggregatorJob)
  - Status (RUNNING)
  - Start time

**Output ví dụ:**
```
------------------ Running/Restarting Jobs -------------------
22.11.2024 15:30:45 : abc123def456 : Sales Transaction Processor (RUNNING)
22.11.2024 15:30:50 : def789ghi012 : Product Sales Aggregator (RUNNING)
--------------------------------------------------------------
```

**Nếu không có jobs nào:**
- Sẽ hiển thị: "No running jobs."
- Cần deploy lại jobs (xem phần Deploy Jobs)

---

## 🧪 Testing Commands (Windows)

### Send Test Transactions

```powershell
# Bước 1: Thiết lập access token (lấy từ Laravel API)
# Step 1: Set access token (get from Laravel API)
$TOKEN = "your_access_token_here"

# Bước 2: Gửi 5 giao dịch test vào Kafka
# Step 2: Send 5 test transactions to Kafka
1..5 | ForEach-Object {
    curl -X POST http://localhost:8000/api/kafka/transactions/sales `
      -H "Authorization: Bearer $TOKEN" `
      -H "Content-Type: application/json" `
      -d "{`"order_id`": $_}"
    Write-Host "Sent transaction $_"
    Start-Sleep -Seconds 1
}
```

**Giải thích chi tiết:**

**Dòng 1:** `$TOKEN = "your_access_token_here"`
- Lưu access token vào biến để xác thực API
- Token này được tạo khi login vào Laravel backend

**Dòng 2:** `1..5 | ForEach-Object { ... }`
- `1..5`: Tạo array từ 1 đến 5 (PowerShell range operator)
- `|`: Pipe operator - đưa output sang lệnh tiếp theo
- `ForEach-Object`: Loop qua từng số từ 1 đến 5
- `$_`: Biến đại diện cho item hiện tại trong loop

**Lệnh curl:**
- `-X POST`: HTTP method là POST
- `http://localhost:8000/api/kafka/transactions/sales`: Endpoint API
- `-H "Authorization: Bearer $TOKEN"`: Header xác thực với Bearer token
- `-H "Content-Type: application/json"`: Header chỉ định dữ liệu JSON
- `-d "{\"order_id\": $_}"`: Data body JSON với order_id = số hiện tại
  - Dấu backtick (`) escape dấu ngoặc kép trong PowerShell
- `Write-Host`: In thông báo đã gửi transaction thứ mấy
- `Start-Sleep -Seconds 1`: Đợi 1 giây giữa mỗi request (tránh spam)

---

### Check Database (Windows)

```powershell
# 1. Đếm tổng số giao dịch (Count total transactions)
docker exec postgres psql -U postgres -d lensart_events `
  -c "SELECT COUNT(*) FROM sales_transactions;"

# 2. Xem 10 giao dịch mới nhất (View latest 10 transactions)
docker exec postgres psql -U postgres -d lensart_events `
  -c "SELECT * FROM sales_transactions ORDER BY created_at DESC LIMIT 10;"

# 3. Xem top 10 sản phẩm có doanh thu cao nhất (View top 10 products by revenue)
docker exec postgres psql -U postgres -d lensart_events `
  -c "SELECT * FROM product_sales ORDER BY total_revenue DESC LIMIT 10;"

# 4. Xem tổng quan doanh số (Sales summary view)
docker exec postgres psql -U postgres -d lensart_events `
  -c "SELECT * FROM v_sales_summary;"
```

**Giải thích chi tiết từng query:**

**Query 1:** `SELECT COUNT(*) FROM sales_transactions`
- Đếm tổng số records trong bảng `sales_transactions`
- Kiểm tra xem Flink job có đang xử lý và lưu data không

**Query 2:** `SELECT * FROM sales_transactions ORDER BY created_at DESC LIMIT 10`
- `SELECT *`: Lấy tất cả columns
- `FROM sales_transactions`: Từ bảng giao dịch bán hàng
- `ORDER BY created_at DESC`: Sắp xếp theo thời gian tạo, mới nhất trước
- `LIMIT 10`: Chỉ lấy 10 records

**Query 3:** `SELECT * FROM product_sales ORDER BY total_revenue DESC LIMIT 10`
- Lấy từ bảng `product_sales` (được tạo bởi aggregation job)
- `ORDER BY total_revenue DESC`: Sắp xếp theo doanh thu giảm dần
- Xem sản phẩm nào bán chạy nhất

**Query 4:** `SELECT * FROM v_sales_summary`
- `v_sales_summary`: View tổng hợp được định nghĩa trong schema
- Hiển thị thống kê tổng quan về doanh số

**Tham số chung:**
- `-U postgres`: Username
- `-d lensart_events`: Database name
- `-c "..."`: Command - SQL query cần thực thi
- Dấu backtick (`): Line continuation trong PowerShell

---

## 🔄 Daily Operations (Windows)

### Start Everything

```powershell
# Bước 1: Di chuyển vào thư mục docker
# Step 1: Navigate to docker directory
cd data_pipeline\docker

# Bước 2: Khởi động tất cả services trong docker-compose.yml
# Step 2: Start all services defined in docker-compose.yml
docker-compose up -d

# Bước 3: Đợi 60 giây để services khởi động hoàn tất
# Step 3: Wait 60 seconds for services to fully start
Start-Sleep -Seconds 60

# Bước 4: Kiểm tra trạng thái các services
# Step 4: Check status of all services
docker-compose ps
```

**Giải thích chi tiết:**

**Lệnh:** `docker-compose up -d`
- `docker-compose`: Tool quản lý multi-container Docker applications
- `up`: Tạo và khởi động containers
- `-d`: Detached mode (chạy nền, không chiếm terminal)
- Đọc file `docker-compose.yml` để biết cần start services gì
- Services bao gồm:
  - **Zookeeper**: Quản lý Kafka cluster
  - **Kafka**: Message broker
  - **PostgreSQL**: Database lưu trữ kết quả
  - **Flink JobManager**: Quản lý Flink jobs
  - **Flink TaskManager**: Thực thi Flink jobs

**Lệnh:** `Start-Sleep -Seconds 60`
- PowerShell cmdlet tạm dừng execution
- Đợi services khởi động đầy đủ trước khi làm việc tiếp
- Kafka và Flink cần thời gian để initialize

**Lệnh:** `docker-compose ps`
- Hiển thị status của tất cả containers
- Cột quan trọng: State (Up = đang chạy, Exit = đã dừng)

---

### Stop Everything

```powershell
cd data_pipeline\docker
docker-compose stop
```

---

### Restart Just Flink Jobs

```powershell
# List and cancel jobs manually
docker exec flink-jobmanager flink list -r

# Cancel jobs (use actual IDs from above)
docker exec flink-jobmanager flink cancel <JOB_ID_1>
docker exec flink-jobmanager flink cancel <JOB_ID_2>

# Redeploy (see Deploy Jobs section above)
```

---

## 📊 Monitoring Commands (Windows)

### Check Everything

```powershell
# Check Docker containers
docker ps

# Check Flink jobs
docker exec flink-jobmanager flink list

# Check tables
docker exec postgres psql -U postgres -d lensart_events -c "\dt"

# Check transaction count
docker exec postgres psql -U postgres -d lensart_events `
  -c "SELECT COUNT(*) FROM sales_transactions;"

# Check product stats count
docker exec postgres psql -U postgres -d lensart_events `
  -c "SELECT COUNT(*) FROM product_sales;"
```

---

### View Logs

```powershell
# 1. Xem logs của Flink JobManager (100 dòng cuối)
# View Flink JobManager logs (last 100 lines)
docker logs flink-jobmanager --tail 100

# 2. Xem logs của Flink TaskManager (100 dòng cuối)
# View Flink TaskManager logs (last 100 lines)
docker logs flink-taskmanager --tail 100

# 3. Xem logs của Kafka (50 dòng cuối)
# View Kafka logs (last 50 lines)
docker logs kafka --tail 50

# 4. Xem logs của PostgreSQL (50 dòng cuối)
# View PostgreSQL logs (last 50 lines)
docker logs postgres --tail 50

# 5. Theo dõi logs real-time (nhấn Ctrl+C để dừng)
# Follow logs in real-time (press Ctrl+C to stop)
docker logs flink-taskmanager -f
```

**Giải thích chi tiết:**

**Lệnh:** `docker logs <container_name> --tail <N>`
- `docker logs`: Xem logs từ container
- `<container_name>`: Tên container cần xem logs
- `--tail <N>`: Chỉ hiển thị N dòng cuối cùng
  - Tránh bị spam quá nhiều logs cũ

**Lệnh:** `docker logs <container_name> -f`
- `-f` hoặc `--follow`: Follow mode (real-time streaming)
- Logs sẽ hiện liên tục khi có events mới
- Giống như `tail -f` trên Linux
- Nhấn `Ctrl+C` để thoát

**Khi nào xem logs nào:**
- **JobManager logs**: 
  - Khi jobs không deploy được
  - Khi muốn xem job status changes
  - Khi có lỗi về job management
  
- **TaskManager logs**: 
  - Khi jobs đang chạy nhưng không xử lý data
  - Khi muốn xem chi tiết processing
  - Khi debug business logic errors
  
- **Kafka logs**: 
  - Khi messages không được gửi vào topics
  - Khi có connection issues với producers/consumers
  
- **PostgreSQL logs**: 
  - Khi data không được lưu vào database
  - Khi có SQL errors hoặc connection issues

---

## 🐛 Troubleshooting (Windows)

### Reset Everything

```powershell
# Clear data
docker exec postgres psql -U postgres -d lensart_events `
  -c "TRUNCATE sales_transactions, product_sales;"

# List jobs
docker exec flink-jobmanager flink list -r

# Cancel each job manually (copy IDs from above)
docker exec flink-jobmanager flink cancel <JOB_ID>

# Restart Docker services
cd data_pipeline\docker
docker-compose restart
```

---

### Check Kafka Messages

```powershell
# Xem messages trong Kafka topic "order-created"
# View messages in Kafka topic "order-created"
docker exec kafka kafka-console-consumer.sh `
  --bootstrap-server localhost:9092 `
  --topic order-created `
  --from-beginning `
  --max-messages 10
```

**Giải thích chi tiết:**

**Lệnh:** `kafka-console-consumer.sh`
- Script của Kafka để consume (đọc) messages từ topic
- Chạy trong container kafka

**Các tham số:**
- `--bootstrap-server localhost:9092`: 
  - Địa chỉ Kafka broker
  - Port 9092 là port mặc định của Kafka
  - `localhost` vì đang exec trong container kafka

- `--topic order-created`: 
  - Tên topic cần đọc messages
  - Topic này chứa events về orders được tạo

- `--from-beginning`: 
  - Đọc từ message đầu tiên trong topic
  - Không dùng flag này sẽ chỉ đọc messages mới

- `--max-messages 10`: 
  - Chỉ đọc tối đa 10 messages rồi dừng
  - Tránh spam quá nhiều messages

**Output ví dụ:**
```json
{"order_id": 1, "customer_id": 123, "total": 150.50, ...}
{"order_id": 2, "customer_id": 456, "total": 299.99, ...}
...
```

**Use cases:**
- Verify rằng Laravel backend đã gửi messages vào Kafka thành công
- Debug format của messages
- Kiểm tra xem Flink có consume được messages không

---

## 📋 Quick Reference Card (Windows)

```powershell
# 1. Khởi động tất cả services (Start all services)
cd data_pipeline\docker
docker-compose up -d

# 2. Build Flink jobs với Maven
cd ..\flink-jobs
mvn clean package -DskipTests

# 3. Quay lại thư mục gốc (Return to root)
cd ..\..

# 4. Deploy jobs
# Bước 4.1: Tạo thư mục trong container
docker exec flink-jobmanager mkdir -p /opt/flink/usrlib

# Bước 4.2: Set JAR path và copy (thay đổi path theo workspace của bạn)
$jarPath = "D:\UIT\HK5\WebDevelopment\MyProject\LensArtEyewear\lensart_eyewear_backend\data_pipeline\flink-jobs\target\lensart-sales-pipeline-1.0.0.jar"
docker cp $jarPath flink-jobmanager:/opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar

# Bước 4.3: Deploy cả 2 jobs
docker exec flink-jobmanager flink run -d -c com.lensart.pipeline.SalesTransactionJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar
docker exec flink-jobmanager bash -c "export WINDOW_SIZE_MINUTES=5 && flink run -d -c com.lensart.pipeline.ProductSalesAggregatorJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar"

# 5. Kiểm tra trạng thái jobs (Check job status)
docker exec flink-jobmanager flink list -r

# 6. Dừng tất cả (Stop everything)
cd data_pipeline\docker
docker-compose stop
```

---

## ⚠️ Important Notes for Windows

1. **Use PowerShell** (not CMD) for best compatibility
2. **Backticks** (\`) for line continuation in PowerShell (not backslash)
3. **Paths** use backslashes (\\) on Windows
4. **No grep/awk** - those are Linux commands, won't work directly
5. **Docker exec** commands work the same on all platforms

---

## 🎯 Common Issues on Windows

### Issue: Command not recognized

**Wrong:**
```bash
docker exec flink-jobmanager bash -c '  for job in $(flink list -r | grep ":" | awk "{print \$4}"); do    flink cancel $job  done'
```
❌ This tries to run grep on Windows

**Right:**
```powershell
# Cancel jobs manually (see Method 1 above)
docker exec flink-jobmanager flink list -r
docker exec flink-jobmanager flink cancel <JOB_ID>
```

---

### Issue: Path not found

**Wrong:**
```bash
docker cp data_pipeline/docker/postgres/file.sql ...
```
❌ Forward slashes might cause issues

**Right:**
```powershell
docker cp data_pipeline\docker\postgres\file.sql ...
```
✅ Use backslashes on Windows

---

### Issue: Long commands

**Use backticks for line continuation:**
```powershell
docker exec flink-jobmanager flink run -d `
  -c com.lensart.pipeline.SalesTransactionJob `
  /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar
```

---

### Issue: Directory not found in container

**Lỗi:**
```
Error response from daemon: Could not find the file /opt/flink/usrlib in container flink-jobmanager
```

**Nguyên nhân:**
- Thư mục `/opt/flink/usrlib` chưa tồn tại trong container
- Container Flink mặc định không có thư mục này

**Giải pháp (QUAN TRỌNG - Phải làm trước khi copy JAR):**
```powershell
# Tạo thư mục trước
docker exec flink-jobmanager mkdir -p /opt/flink/usrlib

# Sau đó mới copy JAR
$jarPath = "D:\UIT\HK5\WebDevelopment\MyProject\LensArtEyewear\lensart_eyewear_backend\data_pipeline\flink-jobs\target\lensart-sales-pipeline-1.0.0.jar"
docker cp $jarPath flink-jobmanager:/opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar
```

**Giải thích:**
- `mkdir -p`: Tạo thư mục (và các thư mục cha nếu cần)
- `-p`: Không báo lỗi nếu thư mục đã tồn tại
- Phải chạy lệnh này MỘT LẦN trước khi deploy

---

### Issue: Docker tar writer error / Path resolution error

**Lỗi:**
```
Error: Can't add file to tar: io: read/write on closed pipe
Error: error while creating mount source path '/run/desktop/mnt/host/d/UIT/HK5/Web Development/...'
```

**Nguyên nhân:**
- Docker Desktop trên Windows đôi khi nhầm lẫn đường dẫn
- Đường dẫn tương đối bị resolve sai thành path có khoảng trắng
- Docker cố gắng mount path không đúng

**Giải pháp 1: Sử dụng đường dẫn tuyệt đối (Khuyến nghị):**
```powershell
# Tạo thư mục trước (QUAN TRỌNG!)
docker exec flink-jobmanager mkdir -p /opt/flink/usrlib

# Dùng đường dẫn tuyệt đối
$jarPath = "D:\UIT\HK5\WebDevelopment\MyProject\LensArtEyewear\lensart_eyewear_backend\data_pipeline\flink-jobs\target\lensart-sales-pipeline-1.0.0.jar"
docker cp $jarPath flink-jobmanager:/opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar
```

**Giải pháp 2: Navigate vào thư mục trước:**
```powershell
# Tạo thư mục trước
docker exec flink-jobmanager mkdir -p /opt/flink/usrlib

# Di chuyển vào thư mục chứa file, sau đó copy trực tiếp
cd data_pipeline\flink-jobs\target
docker cp lensart-sales-pipeline-1.0.0.jar flink-jobmanager:/opt/flink/usrlib/
cd ..\..\..
```

**Kiểm tra file đã copy thành công:**
```powershell
# Xác nhận file đã có trong container
docker exec flink-jobmanager ls -lh /opt/flink/usrlib/

# Kết quả mong đợi:
# -rw-r--r-- 1 flink flink 6.8M Nov 22 16:00 lensart-sales-pipeline-1.0.0.jar
```

**Ý nghĩa:**
- `mkdir -p`: Tạo thư mục nếu chưa có
- `ls -lh`: List files với format dễ đọc (human-readable) để xác nhận
- File size khoảng 6.8MB là đúng

**Nếu thấy "Successfully copied" nhưng vẫn có error:**
- Đừng lo! File đã được copy thành công
- Lỗi tar writer chỉ là warning của Docker Desktop trên Windows
- Tiếp tục deploy jobs bình thường:

```powershell
# Deploy cả 2 jobs
docker exec flink-jobmanager flink run -d -c com.lensart.pipeline.SalesTransactionJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar
docker exec flink-jobmanager bash -c "export WINDOW_SIZE_MINUTES=5 && flink run -d -c com.lensart.pipeline.ProductSalesAggregatorJob /opt/flink/usrlib/lensart-sales-pipeline-1.0.0.jar"

# Xác nhận jobs đã chạy
docker exec flink-jobmanager flink list -r
```

---

**Version:** 1.0.0  
**Platform:** Windows 10/11 PowerShell  
**Last Updated:** 22/11/2024  
**Status:** ✅ Tested on Windows

