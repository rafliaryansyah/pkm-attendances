# Sistem Absensi SMK - PWA

Sistem Absensi berbasis Progressive Web App (PWA) untuk SMK dengan fitur geofencing, approval system, dan reporting.

## 🚀 Fitur Utama

### 1. User Interface (PWA - Mobile First)
- ✅ Check-in/Check-out dengan geofencing (radius 80m)
- ✅ Real-time GPS tracking menggunakan HTML5 Geolocation API
- ✅ Riwayat absensi (5 hari terakhir)
- ✅ Pengajuan izin/sakit dengan upload lampiran
- ✅ Pengajuan revisi absensi (lupa checkout)
- ✅ Profile user dengan informasi lengkap
- ✅ Bottom navigation untuk UX mobile-friendly

### 2. Admin Dashboard (FilamentPHP v3)
- ✅ Manajemen Users (CRUD)
- ✅ Manajemen Absensi dengan filtering
- ✅ Approval izin/sakit dengan catatan admin
- ✅ Approval revisi absensi
- ✅ Dashboard statistik (hadir, telat, izin, sakit, alpha)
- ✅ Reporting dengan filter date range
- ✅ Export data ke Excel/PDF (built-in Filament)

### 3. Business Logic
- ✅ Geofencing dengan Haversine Formula
- ✅ Auto-detect keterlambatan (toleransi 5 menit)
- ✅ Flexible work hours per user
- ✅ Auto-create attendance dari approved permit
- ✅ Revision tracking dengan flag is_revision

## 📋 Requirement

- PHP >= 8.2
- MySQL 8.0
- Composer
- Node.js & NPM (untuk Vite)

## 🛠️ Instalasi

### 1. Clone & Install Dependencies

```bash
cd /path/to/pkm-absensi

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build assets
npm run build
```

### 2. Konfigurasi Environment

File `.env` sudah dikonfigurasi dengan:

```env
APP_NAME="SMK Attendance System"
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=absensi
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Database Setup

```bash
# Generate application key (sudah ada)
php artisan key:generate

# Run migrations
php artisan migrate

# Seed admin user
php artisan db:seed --class=AdminUserSeeder
```

**Admin Default Credentials:**
- Email: `admin@smk.sch.id`
- Password: `password`

### 4. Storage Link

```bash
php artisan storage:link
```

### 5. Run Development Server

```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 📱 PWA Setup

PWA sudah dikonfigurasi dengan:
- Manifest: `/public/manifest.json`
- Service Worker: `/public/sw.js`
- Icons: `/public/images/icon-*.png` (perlu dibuat)

### Membuat PWA Icons

Anda perlu membuat icon dalam berbagai ukuran (72x72, 96x96, 128x128, 144x144, 152x152, 192x192, 384x384, 512x512) dan menyimpannya di `/public/images/`.

## 🗺️ Geofencing Configuration

Lokasi target dan radius dikonfigurasi di `app/Services/GeofencingService.php`:

```php
private const TARGET_LATITUDE = -6.154928;
private const TARGET_LONGITUDE = 106.772240;
private const ALLOWED_RADIUS = 80; // meters
```

Untuk mengubah lokasi, edit konstanta tersebut.

## 📊 Database Schema

### Users
- Extends Laravel default users
- Additional fields: `role`, `phone_number`, `work_start_time`, `work_end_time`, `department`

### Attendances
- Daily attendance logs
- Geolocation tracking (lat_in, long_in, lat_out, long_out)
- Status: present, late, permit, sick, alpha
- Revision flag

### Permits
- Izin/Sakit requests
- Approval workflow with admin notes
- File attachment support

### Attendance Revisions
- Lupa checkout / correction requests
- Links to attendance record
- Approval workflow

## 🎯 User Roles

### Admin
- Full access to dashboard
- Approve/reject permits & revisions
- View reports
- Manage users

### Teacher/Staff
- Mobile PWA interface
- Check-in/out
- Submit permits & revisions
- View personal history

## 📍 Routes

### User PWA
- `/home` - Dashboard & Check-in/out
- `/permits` - Pengajuan izin/sakit
- `/revisions` - Pengajuan revisi
- `/profile` - User profile

### Admin Dashboard
- `/admin` - Filament Admin Panel
- Login dengan admin credentials

### Auth
- `/login` - User login (Breeze)
- `/register` - User registration
- `/logout` - Logout

## 🔧 Services

### GeofencingService
Implementasi Haversine Formula untuk validasi lokasi user.

### AttendanceService
Handle clock-in/out logic, status determination, dan dashboard statistics.

### ApprovalService
Handle approval workflow untuk permits dan revisions.

### ReportingService
Generate reports dengan custom date range dan monthly reports (24th - 23rd).

## 📦 Tech Stack

- **Backend**: Laravel 11.x
- **Database**: MySQL 8.0
- **Admin Panel**: FilamentPHP v3
- **Frontend**: Blade + Livewire 3
- **CSS**: TailwindCSS
- **Auth**: Laravel Breeze
- **PWA**: Custom manifest + service worker

## 🎨 Customization

### Mengubah Jam Kerja Default

Edit di migration atau langsung di database:

```php
'work_start_time' => '06:30:00',
'work_end_time' => '15:00:00',
```

### Mengubah Toleransi Keterlambatan

Edit di `app/Services/AttendanceService.php`:

```php
$toleranceMinutes = 5; // Ubah sesuai kebutuhan
```

### Mengubah Period Laporan Bulanan

Edit di `app/Services/ReportingService.php`:

```php
$startDate = Carbon::create($year, $month, 1)->subMonth()->day(24); // Ubah day(24)
$endDate = Carbon::create($year, $month, 23); // Ubah day(23)
```

## 🐛 Troubleshooting

### GPS tidak berfungsi
- Pastikan HTTPS enabled (required for geolocation)
- Check browser permissions
- Test di device dengan GPS

### Error migration
```bash
php artisan migrate:fresh --seed
```

### Assets tidak load
```bash
npm run build
php artisan optimize:clear
```

## 📝 Notes

- Sistem ini adalah **Single Tenant** (satu sekolah)
- Geofencing **HARUS** di-verify di backend untuk keamanan
- Service Worker cache di-clear otomatis saat versi berubah
- File upload untuk permits tersimpan di `storage/app/public/permits`

## 🤝 Support

Untuk pertanyaan atau issue, silakan hubungi developer atau buat issue di repository.

## 📄 License

This project is private and proprietary.

---

**Developed with ❤️ for SMK**
