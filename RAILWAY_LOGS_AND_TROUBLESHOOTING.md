# Railway Logs & Troubleshooting Guide

## 🔍 Cara Melihat Logs di Railway

### Method 1: Railway Dashboard (Web UI)
1. Buka https://railway.app
2. Login dan pilih project Anda
3. Klik service yang ingin dilihat logsnya
4. Klik tab **"Deployments"**
5. Klik deployment yang sedang running (hijau)
6. **Logs akan otomatis muncul** di bagian bawah
7. Atau klik tab **"Observability"** untuk view logs lebih detail

**Tips:**
- Logs real-time, otomatis update
- Bisa filter by keyword
- Bisa download logs

### Method 2: Railway CLI (Command Line)

Install Railway CLI:
```bash
# macOS
brew install railway

# npm
npm i -g @railway/cli

# Windows
npm i -g @railway/cli
```

View logs:
```bash
# Login first
railway login

# Link to project
railway link

# View logs (real-time)
railway logs

# View logs for specific service
railway logs --service <service-name>

# Follow logs (like tail -f)
railway logs -f
```

## 🚨 Common Errors & Solutions

### 1. APP_KEY Error: "Unsupported cipher or incorrect key length"

**Error Message:**
```
Unsupported cipher or incorrect key length. 
Supported ciphers are: aes-128-cbc, aes-256-cbc, aes-128-gcm, aes-256-gcm.
```

**Cause:** APP_KEY tidak valid, kosong, atau format salah

**Solution:**

#### A. Generate APP_KEY Baru

**Option 1 - Menggunakan Docker:**
```bash
docker run --rm php:8.3-alpine sh -c "php -r \"echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;\""
```

**Option 2 - Menggunakan Online Generator:**
1. Buka: https://generate-random.org/laravel-key-generator
2. Click "Generate Key"
3. Copy hasil (format: `base64:xxxxx...`)

**Option 3 - Manual via Railway Shell:**
1. Di Railway Dashboard → Service → klik 3 dots → **"Shell"**
2. Run command:
   ```bash
   php artisan key:generate --show
   ```
3. Copy outputnya

#### B. Set APP_KEY di Railway

1. Buka **Railway Dashboard** → pilih service
2. Klik tab **"Variables"**
3. Tambahkan atau update variable:
   ```
   APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
   ```
   (Ganti xxx dengan key yang di-generate)
4. Save
5. **Railway akan auto-redeploy**

**PENTING:** 
- APP_KEY harus format: `base64:` diikuti 43-44 karakter
- Jangan ada spasi
- Jangan pakai tanda kutip

#### C. Verify di Logs

Setelah redeploy, check logs:
```
🔑 Generating application key... (skip jika sudah ada)
✅ Application setup completed!
```

### 2. 500 Internal Server Error (General)

**Causes & Solutions:**

#### A. Check Application Logs
```bash
railway logs | grep -i error
```

Look for:
- `PHP Fatal error`
- `SQLSTATE`
- `Class not found`
- `undefined method`

#### B. Common Issues:

**Missing .env variables:**
```bash
# Check environment variables
railway variables

# Required variables:
APP_KEY=base64:xxx
APP_ENV=production
APP_URL=https://your-app.railway.app
DATABASE_URL=postgresql://...
```

**Database connection:**
```bash
# Check if DATABASE_URL is set
railway variables | grep DATABASE_URL

# Test connection in shell
railway run php artisan migrate:status
```

**Storage permissions:**
```
# Check logs for:
failed to open stream: Permission denied
```

Solution: Already handled in Dockerfile, but verify:
```bash
railway run ls -la storage/
```

**Composer dependencies:**
```bash
# Rebuild to ensure all dependencies installed
railway up --detach
```

### 3. Database Migration Errors

**Error:** Migration failed during deployment

**Solutions:**

#### A. Check Migration Status
```bash
railway run php artisan migrate:status
```

#### B. Run Migrations Manually
```bash
# If auto-migration fails
railway run php artisan migrate --force
```

#### C. Fresh Migration (DANGER: Data Loss!)
```bash
railway run php artisan migrate:fresh --force --seed
```

#### D. Disable Auto-Migration

Edit `docker/entrypoint-railway.sh`:
```bash
# Comment out this line:
# php artisan migrate --force --no-interaction || {
#     echo "⚠️  Migration failed, but continuing..."
# }
```

### 4. Form Redirects to HTTP Instead of HTTPS

**Symptoms:** 
- Submit form (e.g., login) redirects to `http://` instead of `https://`
- Browser shows warning: "The information you're about to submit is not secure"
- After submit, URL changes from `https://` to `http://`

**Cause:** Laravel tidak mendeteksi HTTPS karena aplikasi berada di belakang Railway's reverse proxy.

**Solution:**

#### A. Trust Proxies (Sudah di-setup di `bootstrap/app.php`)

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(
        at: '*',
        headers: Request::HEADER_X_FORWARDED_FOR |
                 Request::HEADER_X_FORWARDED_HOST |
                 Request::HEADER_X_FORWARDED_PORT |
                 Request::HEADER_X_FORWARDED_PROTO
    );
})
```

#### B. Force HTTPS (Sudah di-setup di `AppServiceProvider.php`)

```php
if ($this->app->environment('production')) {
    URL::forceScheme('https');
}
```

#### C. Verify Environment Variables

```bash
APP_ENV=production
APP_URL=https://your-app.railway.app
```

**After Fix:**
- Redeploy aplikasi: `git push origin main`
- Forms akan redirect ke HTTPS
- No security warnings

### 5. Asset/CSS Not Loading

**See:** [RAILWAY_DEPLOYMENT.md](./RAILWAY_DEPLOYMENT.md) → Troubleshooting → CSS/JS Assets Not Loading

Quick fix:
```bash
# Set environment variables:
APP_ENV=production
APP_URL=https://your-app.railway.app
ASSET_URL=https://your-app.railway.app
```

### 5. Port Binding Issues

**Error:** `Address already in use` or `Failed to bind to port`

**Cause:** Railway uses dynamic PORT

**Solution:** Already handled in Docker, but verify:
```bash
# Check environment
railway variables | grep PORT

# Should see:
PORT=8080  # or other dynamic port
```

### 6. Build Failed

**Check build logs:**
1. Railway Dashboard → Deployments
2. Click failed deployment (red)
3. View build logs

**Common causes:**
- Dockerfile syntax error
- Missing dependencies
- npm/composer install failed
- Disk space exceeded

**Solutions:**
```bash
# Test build locally:
docker build -t test .

# Check Railway disk usage:
railway run df -h
```

## 🔧 Debug Mode (HATI-HATI!)

**JANGAN aktifkan di production!** Hanya untuk troubleshooting sementara.

### Enable Debug Temporarily

1. Railway Dashboard → Variables
2. Set: `APP_DEBUG=true`
3. Buka app di browser untuk lihat error detail
4. **SEGERA set kembali:** `APP_DEBUG=false`

**WARNING:** Debug mode expose:
- Database credentials
- File paths
- Stack traces
- Environment variables

## 📊 Monitoring & Health Check

### Check Application Health
```bash
# Check if app is running
curl -I https://your-app.railway.app

# Should return:
HTTP/2 200 OK
```

### Check Resource Usage

Railway Dashboard → Service → **Metrics** tab:
- CPU usage
- Memory usage
- Network traffic
- Response times

### Check Database Connection
```bash
railway run php artisan tinker

# In tinker:
DB::connection()->getPdo();
// Should not throw error
```

## 🆘 Getting Help

### 1. Check Logs First
```bash
railway logs -f
```

### 2. Check Railway Status
https://status.railway.app

### 3. Railway Community
- Discord: https://discord.gg/railway
- GitHub Discussions: https://github.com/railwayapp/railway/discussions

### 4. Laravel Logs

If you can access shell:
```bash
railway run cat storage/logs/laravel.log | tail -100
```

## 📋 Quick Debug Checklist

Saat app error, check:

- [ ] Logs di Railway dashboard
- [ ] APP_KEY sudah di-set?
- [ ] APP_ENV=production?
- [ ] APP_URL benar (HTTPS)?
- [ ] DATABASE_URL ada dan valid?
- [ ] Migration sudah run?
- [ ] Storage permissions OK?
- [ ] Build successful?
- [ ] Deployment running (green)?

## 🎯 Most Common Fix

**90% masalah** solved dengan:

```bash
# 1. Set proper environment variables
APP_KEY=base64:xxx
APP_ENV=production
APP_URL=https://your-app.railway.app

# 2. Redeploy
git push origin main

# 3. Check logs
railway logs
```

---

**Remember:** Always check logs first! Logs contain 90% of the answers.
