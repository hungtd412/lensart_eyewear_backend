# 🔧 Azure CORS & Sanctum Configuration Guide

## 📋 Tổng quan

Khi deploy lên Azure, bạn cần cấu hình CORS và Sanctum để cho phép frontend gọi API từ domain của Azure.

---

## ✅ Các thay đổi đã thực hiện

### 1. CORS Configuration (`config/cors.php`)
- ✅ Đã cập nhật để sử dụng environment variable `CORS_ALLOWED_ORIGINS`
- ✅ Hỗ trợ cả local development và production

### 2. Sanctum Configuration (`config/sanctum.php`)
- ✅ Đã có sẵn support cho environment variable `SANCTUM_STATEFUL_DOMAINS`
- ✅ Default values bao gồm localhost ports

---

## 🚀 Cấu hình cho Azure Deployment

### Bước 1: Xác định Frontend URL

Bạn cần biết URL của frontend khi deploy lên Azure. Ví dụ:
- Frontend URL: `https://lensart-frontend.azurewebsites.net`
- Hoặc custom domain: `https://lensart.com`

### Bước 2: Cấu hình trong Azure Portal

1. Vào **Azure Portal** → Web App (backend) → **Configuration**
2. Tab **Application settings**
3. Thêm các biến môi trường sau:

#### CORS Configuration

**Tên:** `CORS_ALLOWED_ORIGINS`  
**Giá trị:** 
```
http://localhost:5173,http://localhost:3000,https://lensart-frontend.azurewebsites.net
```

**Lưu ý:** 
- Thay `https://lensart-frontend.azurewebsites.net` bằng URL thực tế của frontend
- Nếu có nhiều frontend domains, phân cách bằng dấu phẩy
- Không có khoảng trắng sau dấu phẩy

#### Sanctum Configuration

**Tên:** `SANCTUM_STATEFUL_DOMAINS`  
**Giá trị:**
```
localhost,localhost:3000,localhost:5173,127.0.0.1,127.0.0.1:8000,::1,lensart-frontend.azurewebsites.net
```

**Lưu ý:**
- Thay `lensart-frontend.azurewebsites.net` bằng domain thực tế của frontend (không có `https://`)
- Bao gồm cả localhost để có thể test local
- Nếu có custom domain, thêm vào đây

### Bước 3: Restart Web App

Sau khi thêm environment variables:
1. Click **Save** ở trên cùng
2. Restart Web App
3. Đợi 1-2 phút để app restart

---

## 📝 Ví dụ cấu hình đầy đủ

### Scenario 1: Frontend và Backend trên Azure

**Backend:** `https://lensart-backend.azurewebsites.net`  
**Frontend:** `https://lensart-frontend.azurewebsites.net`

**Environment Variables:**

```
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://localhost:3000,https://lensart-frontend.azurewebsites.net

SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,localhost:5173,127.0.0.1,127.0.0.1:8000,::1,lensart-frontend.azurewebsites.net
```

### Scenario 2: Custom Domain

**Backend:** `https://api.lensart.com`  
**Frontend:** `https://www.lensart.com`

**Environment Variables:**

```
CORS_ALLOWED_ORIGINS=http://localhost:5173,http://localhost:3000,https://www.lensart.com

SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,localhost:5173,127.0.0.1,127.0.0.1:8000,::1,www.lensart.com
```

---

## 🔍 Verify Configuration

### 1. Check CORS Headers

Test bằng cách gọi API từ frontend và check response headers:

```bash
curl -H "Origin: https://lensart-frontend.azurewebsites.net" \
     -H "Access-Control-Request-Method: GET" \
     -X OPTIONS \
     https://lensart-backend.azurewebsites.net/api/newest-products \
     -v
```

**Expected response:**
```
Access-Control-Allow-Origin: https://lensart-frontend.azurewebsites.net
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: *
```

### 2. Check Sanctum Authentication

Test login và verify token được tạo:

```bash
# Login
curl -X POST https://lensart-backend.azurewebsites.net/api/auth/login \
     -H "Content-Type: application/json" \
     -H "Origin: https://lensart-frontend.azurewebsites.net" \
     -d '{"email":"user@example.com","password":"password"}'

# Response should include token
```

### 3. Test API với Token

```bash
# Get token from login response
TOKEN="your_token_here"

# Call protected API
curl -X GET https://lensart-backend.azurewebsites.net/api/users/profile \
     -H "Authorization: Bearer $TOKEN" \
     -H "Origin: https://lensart-frontend.azurewebsites.net"
```

---

## 🐛 Troubleshooting

### Lỗi: CORS policy blocked

**Nguyên nhân:** Frontend URL không có trong `CORS_ALLOWED_ORIGINS`

**Fix:**
1. Check giá trị của `CORS_ALLOWED_ORIGINS` trong Azure Portal
2. Đảm bảo URL frontend chính xác (có/không có trailing slash)
3. Restart Web App sau khi thay đổi

### Lỗi: 401 Unauthorized

**Nguyên nhân:** 
- Token không hợp lệ
- Frontend domain không có trong `SANCTUM_STATEFUL_DOMAINS`
- Token không được gửi đúng cách

**Fix:**
1. Check `SANCTUM_STATEFUL_DOMAINS` có chứa frontend domain
2. Verify frontend gửi token trong header: `Authorization: Bearer {token}`
3. Check token chưa hết hạn

### Lỗi: Config không áp dụng

**Nguyên nhân:** Cache config chưa được clear

**Fix:**
1. SSH vào Azure Web App:
   ```bash
   az webapp ssh --name lensart --resource-group lensart-rg
   ```
2. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
3. Hoặc thêm vào `startup.sh`:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

---

## 📋 Checklist

Trước khi deploy:

- [ ] Xác định frontend URL (Azure hoặc custom domain)
- [ ] Thêm `CORS_ALLOWED_ORIGINS` vào Azure Portal
- [ ] Thêm `SANCTUM_STATEFUL_DOMAINS` vào Azure Portal
- [ ] Restart Web App
- [ ] Test CORS headers
- [ ] Test authentication flow
- [ ] Test protected APIs

---

## 💡 Tips

1. **Development vs Production:**
   - Local: Sử dụng default values trong config
   - Production: Set environment variables trong Azure Portal

2. **Multiple Environments:**
   - Có thể tạo nhiều Web Apps (staging, production)
   - Mỗi app có environment variables riêng

3. **Security:**
   - Chỉ thêm domains cần thiết vào CORS
   - Không dùng wildcard `*` cho production

4. **Testing:**
   - Test cả local và production
   - Verify cả HTTP và HTTPS

---

## ✅ Sau khi cấu hình

Sau khi set environment variables và restart:

1. ✅ CORS sẽ cho phép requests từ frontend domain
2. ✅ Sanctum sẽ authenticate requests từ frontend domain
3. ✅ Bearer token authentication sẽ hoạt động
4. ✅ Không còn lỗi 401 Unauthorized

---

**🎉 Done! Bây giờ bạn có thể deploy và test trên Azure.**

