# 🚀 Startup Script - Quick Guide

## ✅ Files đã tạo

| File | Location | Purpose |
|------|----------|---------|
| `startup.sh` | Root Laravel | Script chạy khi deploy |
| `.deployment` | Root Laravel | Tell Azure to use startup.sh |
| `AZURE_DEPLOYMENT_GUIDE.md` | Root Laravel | Full documentation |

---

## 🎯 SETUP - 3 Steps

### 1️⃣ Configure trong Azure Portal

**Trên screenshot bạn vừa gửi:**

1. Bạn đang ở: **Configuration (preview)** → **Stack settings**
2. Kéo xuống phần **"Startup command"**
3. Nhập:
   ```
   /home/site/wwwroot/startup.sh
   ```
4. Click **"Save"** (ở trên cùng)

---

### 2️⃣ Deploy Files

```powershell
cd D:\UIT\HK5\WebDevelopment\MyProject\LensArtEyewear\lensart_eyewear_backend

# Add new files
git add startup.sh .deployment AZURE_DEPLOYMENT_GUIDE.md
git commit -m "Add Azure startup script"

# Deploy (nếu đã setup git remote)
git push azure main
```

**Hoặc upload qua FTP/ZIP.**

---

### 3️⃣ Restart Web App

1. Web App → **Overview**
2. Click **"Restart"**
3. Đợi 2-3 phút
4. Check logs

---

## 📊 Verify Startup Script Running

### Check via Log Stream:

1. Web App → **"Log stream"**
2. Sau khi restart, sẽ thấy:

```
🚀 Starting LensArt Laravel Application
📦 Step 1: Setting up environment...
📦 Step 2: Installing Composer dependencies...
🔐 Step 3: Setting storage permissions...
⚡ Step 4: Running Laravel optimizations...
✅ LensArt Laravel Application Ready!
```

### Check via SSH:

```bash
az webapp ssh --name lensart --resource-group lensart-rg

# Inside SSH
cd /home/site/wwwroot
cat startup.sh
ls -la startup.sh  # Check permissions
```

---

## 🔧 Common Issues

### ❌ "bash: startup.sh: Permission denied"

**Fix:**
```bash
# Local
chmod +x startup.sh
git add startup.sh
git commit -m "Make startup.sh executable"
git push azure main
```

### ❌ "startup.sh: not found"

**Fix:** Đường dẫn sai trong Startup Command

**Try:**
- `/home/site/wwwroot/startup.sh`
- `bash /home/site/wwwroot/startup.sh`
- `/home/startup.sh` (if using .deployment)

### ❌ Script chạy nhưng app không start

**Check logs:**
```bash
az webapp log tail --name lensart --resource-group lensart-rg
```

**Common causes:**
- Composer dependencies fail
- Permission issues
- .env file missing
- Database connection fail

---

## 📋 What Startup Script Does

```
1. ✅ Check .env file exists (copy from .env.production)
2. ✅ Install Composer dependencies (production only)
3. ✅ Set storage/cache permissions (775)
4. ✅ Clear all Laravel caches
5. ✅ Cache config, routes, views for production
6. ✅ Run Laravel optimize
7. ✅ Check application key
8. ✅ Health check
9. ✅ Ready to serve! 🎉
```

---

## 🎯 Next Steps

**After startup script is working:**

1. ✅ Verify app accessible: `https://lensart.azurewebsites.net`
2. ✅ Test API endpoints
3. ✅ Check database connectivity
4. ✅ Test Azure Queue integration
5. ✅ Monitor performance

---

**Location trong screenshot của bạn:**
```
Configuration (preview) → Stack settings → Startup command
↓
Nhập: /home/site/wwwroot/startup.sh
```

**🎉 Done! Save và Restart!**

