# Azure Deployment Guide - Laravel Backend

## 📋 Files cần thiết

### ✅ Đã tạo:
- `startup.sh` - Startup script chạy khi deploy
- `.deployment` - Config file cho Azure deployment

---

## 🚀 Cách configure Startup Command trong Azure Portal

### Option 1: Via Portal Settings (RECOMMENDED)

1. Vào **Azure Portal** → Web App (`lensart`)
2. Menu trái → **"Configuration"**
3. Tab **"General settings"**
4. Section **"Startup Command"**
5. Nhập:
   ```
   /home/startup.sh
   ```
   Hoặc:
   ```
   bash /home/site/wwwroot/startup.sh
   ```
6. Click **"Save"**
7. Restart Web App

### Option 2: Via Azure CLI

```bash
az webapp config set \
  --name lensart \
  --resource-group lensart-rg \
  --startup-file "/home/site/wwwroot/startup.sh"
```

---

## 📦 Deploy Files lên Azure

### Method 1: Git Deploy (RECOMMENDED)

```bash
cd D:\UIT\HK5\WebDevelopment\MyProject\LensArtEyewear\lensart_eyewear_backend

# Add files
git add startup.sh .deployment
git commit -m "Add Azure startup script"

# Push to Azure
git push azure main
```

### Method 2: FTP Deploy

1. Azure Portal → Web App → **"Deployment Center"**
2. Tab **"FTPS credentials"**
3. Copy credentials
4. Upload files:
   - `startup.sh` → `/site/wwwroot/`
   - `.deployment` → `/site/wwwroot/`

### Method 3: ZIP Deploy

```powershell
# Include startup files in deployment
Compress-Archive -Path * -DestinationPath lensart-deploy.zip -Force

az webapp deployment source config-zip `
  --name lensart `
  --resource-group lensart-rg `
  --src lensart-deploy.zip
```

---

## ✅ Startup Script Features

### Các bước trong `startup.sh`:

1. ✅ **Environment Setup**
   - Check .env file exists
   - Copy from .env.production if needed

2. ✅ **Install Dependencies**
   - Run `composer install --no-dev --optimize-autoloader`
   - Skip nếu vendor đã có

3. ✅ **Storage Permissions**
   - Create necessary directories
   - Set chmod 775
   - Set ownership to www-data

4. ✅ **Laravel Optimizations**
   - Clear all caches
   - Cache config, routes, views
   - Run artisan optimize

5. ✅ **Database Migrations** (commented out)
   - Uncomment nếu muốn auto-migrate
   - ⚠️ Use with caution!

6. ✅ **Queue Configuration**
   - Check queue connection
   - Log configuration

7. ✅ **Application Key**
   - Generate if missing
   - Skip nếu đã có

8. ✅ **Health Check**
   - Verify app can boot
   - Show configuration

---

## 🔧 Customize Startup Script

### Enable Auto Migrations

**Edit `startup.sh` dòng 87-90:**

```bash
# OLD (commented)
# php artisan migrate --force

# NEW (uncommented)
php artisan migrate --force
```

⚠️ **WARNING:** Only for development! Production nên migrate manually.

### Add Seeding

**After migrations, add:**

```bash
php artisan db:seed --force
```

### Add Custom Commands

**Add your custom artisan commands:**

```bash
# Example: Clear specific cache
php artisan cache:forget specific-key

# Example: Run custom command
php artisan lensart:setup
```

---

## 🔍 Verify Startup Script

### Test locally (Linux/WSL):

```bash
# Make executable
chmod +x startup.sh

# Run locally
bash startup.sh
```

### View logs trên Azure:

**Real-time logs:**
```bash
az webapp log tail \
  --name lensart \
  --resource-group lensart-rg
```

**Or via Portal:**
```
Web App → Log stream
```

**Expected output:**
```
🚀 Starting LensArt Laravel Application
📦 Step 1: Setting up environment...
📦 Step 2: Installing Composer dependencies...
🔐 Step 3: Setting storage permissions...
⚡ Step 4: Running Laravel optimizations...
✅ LensArt Laravel Application Ready!
```

---

## 🐛 Troubleshooting

### Issue 1: Permission Denied

**Error:**
```
bash: startup.sh: Permission denied
```

**Fix:**

1. Make executable locally:
```bash
git update-index --chmod=+x startup.sh
git commit -m "Make startup.sh executable"
git push azure main
```

2. Or add to startup command:
```
bash /home/site/wwwroot/startup.sh
```

### Issue 2: Composer Install Fails

**Error:**
```
composer: command not found
```

**Fix:** Azure PHP images có sẵn composer, nhưng check version:

```bash
# Via SSH
az webapp ssh --name lensart --resource-group lensart-rg

# Check composer
which composer
composer --version
```

### Issue 3: Script không chạy

**Check:**

1. Startup command configured? 
2. File có ở đúng location `/home/site/wwwroot/startup.sh`?
3. File có executable permission?

**Verify via Kudu:**
```
https://lensart.scm.azurewebsites.net
```

Navigate: `/home/site/wwwroot/` → Check `startup.sh` exists

---

## 📊 Complete Azure Configuration

### Web App Settings cần có:

**In Azure Portal → Configuration → Application settings:**

```
APP_ENV=production
APP_KEY=base64:...
APP_URL=https://lensart.azurewebsites.net
DB_CONNECTION=mysql
DB_HOST=lensart-mysql-server.mysql.database.azure.com
DB_DATABASE=lensart_eyewear
DB_USERNAME=adminuser
DB_PASSWORD=YourPassword
QUEUE_CONNECTION=azure-queue
AZURE_STORAGE_CONNECTION_STRING=DefaultEndpointsProtocol=https;...
```

**In Configuration → General settings:**

```
Stack: PHP 8.2
Startup Command: /home/site/wwwroot/startup.sh
```

---

## 🎯 Deployment Checklist

- [ ] `startup.sh` created
- [ ] `.deployment` created  
- [ ] Files deployed to Azure
- [ ] Startup command configured in Portal
- [ ] Environment variables set
- [ ] Web App restarted
- [ ] Logs show startup script running
- [ ] Application accessible

---

## 🔄 Redeploy Process

Mỗi khi update code:

```bash
# 1. Commit changes
git add .
git commit -m "Update application"

# 2. Push to Azure
git push azure main

# 3. Azure tự động:
#    - Deploy code
#    - Run startup.sh
#    - Restart application
```

**Monitor deployment:**
```bash
az webapp log tail --name lensart --resource-group lensart-rg
```

---

## 📝 Startup Script Execution Time

**Estimated time:**
- First deploy (with composer install): ~3-5 minutes
- Subsequent deploys (vendor exists): ~30-60 seconds

**Timeline:**
```
T+0s:   Deploy triggered
T+10s:  Files copied
T+20s:  Startup script starts
T+30s:  Composer install (if needed)
T+180s: Dependencies installed
T+190s: Laravel optimizations
T+200s: Health check
T+210s: PHP-FPM started
T+220s: Application ready ✅
```

---

## 💡 Tips

### 1. Skip Composer Install for faster deploys

Edit `startup.sh` line 38:
```bash
# OLD - Always install
composer install --no-dev --optimize-autoloader --no-interaction

# NEW - Skip if vendor exists
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    composer install --no-dev --optimize-autoloader --no-interaction
fi
```

### 2. Add Health Check Endpoint

Create route in Laravel:
```php
Route::get('/health', function() {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'services' => [
            'database' => DB::connection()->getPdo() ? 'connected' : 'failed',
            'queue' => config('queue.default')
        ]
    ]);
});
```

### 3. Monitor startup via Application Insights

Azure Portal → Application Insights → Logs:
```kusto
traces
| where message contains "Starting LensArt"
| order by timestamp desc
```

---

**✅ Done! Files created:**
- `startup.sh` - Startup script
- `.deployment` - Deployment config
- `AZURE_DEPLOYMENT_GUIDE.md` - Documentation

**Next steps:**
1. Configure startup command trong Azure Portal
2. Deploy files
3. Monitor logs
4. Test application

🚀 Ready to deploy!
