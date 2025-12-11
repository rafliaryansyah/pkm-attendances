# Panduan Deploy ke Google Cloud Run

## Prerequisites

1. Google Cloud SDK terinstall
2. Docker terinstall
3. Project Google Cloud sudah dibuat
4. Cloud Run API sudah diaktifkan

## Setup Awal

### 1. Konfigurasi Google Cloud

```bash
# Login ke Google Cloud
gcloud auth login

# Set project ID
gcloud config set project YOUR_PROJECT_ID

# Enable required APIs
gcloud services enable run.googleapis.com
gcloud services enable containerregistry.googleapis.com
gcloud services enable cloudbuild.googleapis.com
```

### 2. Setup Database (Cloud SQL)

Untuk production, gunakan Cloud SQL:

```bash
# Buat Cloud SQL instance (PostgreSQL atau MySQL)
gcloud sql instances create pkm-absensi-db \
    --database-version=MYSQL_8_0 \
    --tier=db-f1-micro \
    --region=asia-southeast2

# Buat database
gcloud sql databases create pkm_absensi --instance=pkm-absensi-db

# Buat user
gcloud sql users create laravel_user \
    --instance=pkm-absensi-db \
    --password=STRONG_PASSWORD
```

### 3. Setup Environment Variables

Buat file `.env.production` dengan konfigurasi production:

```env
APP_NAME="PKM Absensi"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-service-name.run.app

DB_CONNECTION=mysql
DB_HOST=/cloudsql/YOUR_PROJECT_ID:REGION:INSTANCE_NAME
DB_PORT=3306
DB_DATABASE=pkm_absensi
DB_USERNAME=laravel_user
DB_PASSWORD=STRONG_PASSWORD

SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error
```

## Build dan Deploy

### Metode 1: Deploy dengan Cloud Build (Recommended)

```bash
# Build dan push image menggunakan Cloud Build
gcloud builds submit --tag gcr.io/YOUR_PROJECT_ID/pkm-absensi

# Deploy ke Cloud Run
gcloud run deploy pkm-absensi \
    --image gcr.io/YOUR_PROJECT_ID/pkm-absensi \
    --platform managed \
    --region asia-southeast2 \
    --allow-unauthenticated \
    --memory 512Mi \
    --cpu 1 \
    --min-instances 0 \
    --max-instances 10 \
    --set-env-vars "APP_ENV=production,APP_DEBUG=false" \
    --set-secrets "APP_KEY=app-key:latest,DB_PASSWORD=db-password:latest" \
    --add-cloudsql-instances YOUR_PROJECT_ID:REGION:INSTANCE_NAME
```

### Metode 2: Build Local dan Push

```bash
# Build image
docker build -t gcr.io/YOUR_PROJECT_ID/pkm-absensi .

# Push ke Container Registry
docker push gcr.io/YOUR_PROJECT_ID/pkm-absensi

# Deploy
gcloud run deploy pkm-absensi \
    --image gcr.io/YOUR_PROJECT_ID/pkm-absensi \
    --platform managed \
    --region asia-southeast2 \
    --allow-unauthenticated
```

## Setup Secret Manager (Recommended)

Untuk keamanan lebih baik, gunakan Secret Manager:

```bash
# Buat secrets
echo -n "base64:YOUR_APP_KEY" | gcloud secrets create app-key --data-file=-
echo -n "YOUR_DB_PASSWORD" | gcloud secrets create db-password --data-file=-

# Berikan akses ke Cloud Run
gcloud secrets add-iam-policy-binding app-key \
    --member=serviceAccount:YOUR_SERVICE_ACCOUNT \
    --role=roles/secretmanager.secretAccessor

gcloud secrets add-iam-policy-binding db-password \
    --member=serviceAccount:YOUR_SERVICE_ACCOUNT \
    --role=roles/secretmanager.secretAccessor
```

## Post-Deployment

### Run Migrations

```bash
# Jalankan migration menggunakan Cloud Run Jobs atau manual
gcloud run jobs create pkm-absensi-migrate \
    --image gcr.io/YOUR_PROJECT_ID/pkm-absensi \
    --command php \
    --args artisan,migrate,--force \
    --set-cloudsql-instances YOUR_PROJECT_ID:REGION:INSTANCE_NAME

# Execute job
gcloud run jobs execute pkm-absensi-migrate
```

Atau edit `docker/entrypoint.sh` dan uncomment baris migration untuk auto-migration.

### Setup Storage

Untuk file storage, pertimbangkan menggunakan Google Cloud Storage:

1. Buat bucket: `gsutil mb gs://pkm-absensi-storage`
2. Install package: `composer require league/flysystem-google-cloud-storage`
3. Konfigurasi di `config/filesystems.php`

## Monitoring

```bash
# Lihat logs
gcloud run services logs read pkm-absensi --region asia-southeast2

# Monitoring di console
# https://console.cloud.google.com/run
```

## Update Aplikasi

```bash
# Build dan deploy versi baru
gcloud builds submit --tag gcr.io/YOUR_PROJECT_ID/pkm-absensi
gcloud run deploy pkm-absensi --image gcr.io/YOUR_PROJECT_ID/pkm-absensi
```

## Troubleshooting

### Port Issues
Cloud Run expects port 8080 (sudah dikonfigurasi di Dockerfile)

### Permission Issues
```bash
# Pastikan service account memiliki permission yang cukup
gcloud projects add-iam-policy-binding YOUR_PROJECT_ID \
    --member=serviceAccount:YOUR_SERVICE_ACCOUNT \
    --role=roles/cloudsql.client
```

### Database Connection
Pastikan menggunakan Unix socket untuk Cloud SQL:
```
DB_HOST=/cloudsql/PROJECT_ID:REGION:INSTANCE_NAME
```

## Cost Optimization

1. Set `--min-instances 0` untuk scale to zero saat tidak digunakan
2. Gunakan `--memory 512Mi` untuk aplikasi kecil
3. Pertimbangkan Cloud SQL tier yang sesuai
4. Enable Cloud CDN untuk static assets

## Keamanan

1. Jangan commit `.env` file
2. Gunakan Secret Manager untuk sensitive data
3. Set `APP_DEBUG=false` di production
4. Gunakan HTTPS (default di Cloud Run)
5. Implementasi rate limiting di Laravel
