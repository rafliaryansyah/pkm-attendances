# Railway.com Deployment Guide

Panduan lengkap untuk deploy aplikasi Laravel PKM Absensi ke Railway.com

## ⚡ Quick Fix - PHP Version

**PENTING:** Dockerfile dan composer.json sudah diupdate ke PHP 8.3 untuk resolve dependency conflicts. Pastikan semua changes sudah di-commit sebelum deploy.

## 📋 Prerequisites

1. Akun Railway.com (https://railway.app)
2. Repository Git yang sudah di-push ke GitHub/GitLab
3. Database PostgreSQL atau MySQL (bisa provision dari Railway)
4. Docker installed (untuk test build locally - optional)

## 🧪 Test Build Locally (Optional)

Sebelum push ke Railway, test Dockerfile locally:

```bash
# Build image
docker build -t pkm-absensi:test .

# Test run (tanpa database)
docker run -p 8080:8080 -e APP_KEY=base64:test123 pkm-absensi:test

# Atau dengan semua env vars
docker run -p 8080:8080 \
  -e APP_KEY=base64:test123 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  pkm-absensi:test
```

Access di http://localhost:8080

## 🚀 Step-by-Step Deployment

### 1. Commit & Push Changes

Pastikan semua file sudah di-commit:
```bash
git add .
git commit -m "Setup Railway deployment with PHP 8.3"
git push origin main
```

### 2. Buat Project Baru di Railway

1. Login ke Railway.app
2. Klik "New Project"
3. Pilih "Deploy from GitHub repo"
4. Pilih repository Anda
5. Railway akan otomatis detect Dockerfile

### 3. Provision Database (Opsional)

Jika belum punya database eksternal:

1. Di project Railway, klik "+ New"
2. Pilih "Database" → "PostgreSQL" atau "MySQL"
3. Railway akan generate DATABASE_URL otomatis
4. Database URL akan otomatis tersedia sebagai environment variable

### 4. Setup Environment Variables

Di Railway dashboard, masuk ke service Anda → Variables tab, tambahkan:

#### Required Variables

```bash
APP_NAME="PKM Absensi"
APP_ENV=production
APP_KEY=                        # Will be auto-generated if empty
APP_DEBUG=false
APP_URL=https://your-app.railway.app

# Database - Jika pakai Railway PostgreSQL, DATABASE_URL sudah auto-inject
# Atau set manual:
DB_CONNECTION=pgsql             # atau mysql
DB_HOST=your-db-host
DB_PORT=5432                    # 5432 untuk postgres, 3306 untuk mysql
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Log
LOG_CHANNEL=stack
LOG_LEVEL=error

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

#### Optional Variables

```bash
# Mail Configuration (jika perlu email)
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Google Maps API (untuk fitur lokasi)
GOOGLE_MAPS_API_KEY=your-api-key
```

### 5. Deploy

1. Railway akan otomatis trigger build setiap kali ada push ke branch main
2. Monitor logs di Railway dashboard
3. Setelah deployment sukses, klik "Settings" → "Networking" untuk lihat public URL

## 🔧 Railway Configuration Files

Project ini sudah include:

### `railway.json`
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "DOCKERFILE",
    "dockerfilePath": "Dockerfile"
  },
  "deploy": {
    "numReplicas": 1,
    "sleepApplication": false,
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

### `Dockerfile`
- Multi-stage build untuk optimize image size
- Support dynamic PORT dari Railway
- Auto-run migrations pada deployment
- Nginx + PHP-FPM dengan Supervisor

### `docker/entrypoint-railway.sh`
Script yang handle:
- Dynamic PORT configuration
- Database connection wait
- Auto-generate APP_KEY jika kosong
- Laravel optimization (config, route, view cache)
- Auto-run migrations
- Storage link setup

## 🔍 Troubleshooting

### CSS/JS Assets Not Loading (Mixed Content)

**Symptoms:** Page shows raw HTML without styling, browser console shows failed to load assets via HTTP.

**Cause:** Laravel generating HTTP URLs instead of HTTPS, causing mixed content errors.

**Solution:**
1. **Pastikan environment variables correct:**
   ```bash
   APP_ENV=production          # MUST be "production"
   APP_URL=https://your-app.railway.app  # MUST use HTTPS
   ASSET_URL=https://your-app.railway.app  # Optional but recommended
   ```

2. **Force HTTPS sudah di-setup di `AppServiceProvider.php`:**
   ```php
   if ($this->app->environment('production')) {
       URL::forceScheme('https');
   }
   ```

3. **Redeploy aplikasi** setelah update environment variables

4. **Clear browser cache** atau buka di incognito mode untuk test

**Verification:**
- Check browser developer tools → Network tab
- Assets should load from `https://your-app.railway.app/build/assets/...`
- Tidak boleh ada request ke `http://` (without 's')

### Database Connection Failed

Pastikan DATABASE_URL format correct:
```bash
# PostgreSQL
postgresql://username:password@host:5432/database

# MySQL
mysql://username:password@host:3306/database
```

### Migration Errors

Check logs di Railway:
```bash
railway logs
```

Atau disable auto-migration di `docker/entrypoint-railway.sh` jika ingin run manual.

### Port Issues

Railway otomatis inject PORT environment variable. Jangan hardcode port 8080.

### Storage/Upload Issues

Pastikan storage sudah linked. Check di entrypoint logs:
```
🔗 Linking storage...
```

## 🎯 Post-Deployment

### 1. Generate Admin User (First Time)

Connect via Railway CLI:
```bash
railway run php artisan tinker
```

Atau gunakan Railway's service terminal di dashboard.

### 2. Setup Cron Jobs (Optional)

Jika aplikasi butuh scheduled tasks, bisa:
1. Tambah service worker terpisah di Railway
2. Atau gunakan external cron service seperti cron-job.org

### 3. Setup Domain Custom

Di Railway Settings → Networking:
1. Klik "Generate Domain" untuk subdomain railway.app
2. Atau "Custom Domain" untuk domain sendiri
3. Update APP_URL di environment variables

## 📊 Monitoring

Railway provides:
- Real-time logs
- Resource usage metrics
- Deployment history
- Health checks

Access via Railway dashboard.

## 💰 Pricing

Railway menggunakan usage-based pricing:
- Free tier: $5 credit/month
- Starter: $5 fixed + usage
- Cek https://railway.app/pricing untuk detail

Estimate untuk Laravel app kecil-menengah: ~$5-15/bulan

## 🔄 CI/CD

Otomatis setup:
- Push ke `main` branch → Auto deploy
- Railway monitor Dockerfile changes
- Rollback available via deployment history

## 📝 Environment Variables Reference

| Variable | Required | Default | Description |
|----------|----------|---------|-------------|
| PORT | Auto-set | 8080 | Railway's dynamic port |
| APP_KEY | Yes | - | Laravel encryption key |
| APP_ENV | Yes | production | Application environment |
| APP_DEBUG | No | false | Debug mode |
| DATABASE_URL | Yes* | - | Complete database URL |
| DB_CONNECTION | No | mysql | Database type |

*DATABASE_URL auto-set jika pakai Railway database

## 🆘 Support

- Railway Docs: https://docs.railway.app
- Railway Discord: https://discord.gg/railway
- Project Issues: GitHub issues

## 🎉 Success Checklist

- [ ] Railway project created
- [ ] Database provisioned or connected
- [ ] Environment variables configured
- [ ] First deployment successful
- [ ] Migrations ran successfully
- [ ] Application accessible via public URL
- [ ] Admin user created
- [ ] Domain configured (optional)

Happy deploying! 🚂
