# 🚀 Quick Start Guide - Sistem Absensi SMK

## Akses Sistem

### 1. Admin Dashboard
**URL**: `http://localhost:8000/admin`

**Default Login:**
- Email: `admin@smk.sch.id`
- Password: `password`

**Fitur Admin:**
- Dashboard dengan statistik hari ini
- Manajemen Users (Teacher/Staff)
- Approval Izin/Sakit
- Approval Revisi Absensi
- Reporting & Export

### 2. User PWA (Mobile App)
**URL**: `http://localhost:8000`

**Test User** (Perlu dibuat manual via admin atau register):
- Bisa register sendiri atau dibuat oleh admin
- Default role: Teacher

**Fitur User:**
- Check-in/Check-out (GPS Required)
- Pengajuan Izin/Sakit
- Pengajuan Revisi Absensi
- Lihat History 5 hari terakhir

## 🎯 Testing Flow

### A. Testing Check-In (Geofencing)

#### 1. Simulasi Lokasi yang Benar
Koordinat SMK:
- Latitude: `-6.154928`
- Longitude: `106.772240`
- Radius: `80 meter`

**Browser DevTools (Chrome):**
1. Open DevTools (F12)
2. Tab "Sensors" (Ctrl+Shift+P > "Show Sensors")
3. Set custom location:
   - Latitude: `-6.154928`
   - Longitude: `106.772240`
4. Klik "Check In" di PWA

#### 2. Simulasi Lokasi di Luar Radius
- Set latitude: `-6.155928` (lebih jauh)
- Klik "Check In"
- Akan muncul error: "Anda berada di luar area absensi"

### B. Testing Status Keterlambatan

**Work Hours Default**: 06:30 - 15:00
**Tolerance**: 5 menit

**Test Cases:**
1. Check-in jam 06:30 → Status: `present`
2. Check-in jam 06:34 → Status: `present` (masih toleransi)
3. Check-in jam 06:36 → Status: `late`

Untuk test ini, bisa modify:
```bash
php artisan tinker
# Update work_start_time user untuk testing
```

### C. Testing Approval Flow

#### Izin/Sakit:
1. User submit izin via PWA (`/permits`)
2. Admin approve di dashboard (`/admin/permits`)
3. Sistem auto-create attendance record

#### Revisi Absensi:
1. User lupa checkout
2. Submit revisi di `/revisions`
3. Admin approve di dashboard (`/admin/attendance-revisions`)
4. Data attendance ter-update otomatis

## 📊 Reporting

### Date Range Report
1. Go to `/admin/attendances`
2. Use filter tanggal
3. Export to Excel (button di pojok kanan)

### Monthly Report (24-23)
Default: Laporan bulanan dari tanggal 24 bulan lalu sampai 23 bulan ini.

Contoh untuk Januari 2025:
- Start: 24 Desember 2024
- End: 23 Januari 2025

## 🔧 Development Commands

```bash
# Clear all cache
php artisan optimize:clear

# Run migrations
php artisan migrate:fresh --seed

# Create new user via tinker
php artisan tinker
>>> User::create([
    'name' => 'Test Teacher',
    'email' => 'teacher@test.com',
    'phone_number' => '081234567891',
    'password' => Hash::make('password'),
    'role' => 'teacher',
    'work_start_time' => '06:30:00',
    'work_end_time' => '15:00:00',
    'department' => 'TKJ',
]);

# Build assets
npm run build

# Run dev server
php artisan serve
```

## 📱 Mobile Testing

### Testing di Mobile Device (Same Network):

1. Find your computer's IP:
```bash
# Mac/Linux
ifconfig | grep inet

# Windows
ipconfig
```

2. Run server:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

3. Access from mobile:
```
http://YOUR_IP:8000
```

### Install as PWA:

**Android (Chrome):**
- Klik menu (⋮) > "Add to Home screen"

**iOS (Safari):**
- Tap Share > "Add to Home Screen"

## 🐛 Common Issues

### 1. GPS Tidak Bekerja
**Solution:**
- Harus HTTPS untuk production
- Atau gunakan `localhost` untuk development
- Check browser permissions

### 2. Livewire Error
```bash
php artisan livewire:publish --config
php artisan livewire:publish --assets
```

### 3. Filament Error
```bash
php artisan filament:upgrade
php artisan filament:assets
```

### 4. Permission Denied (Storage)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📝 Notes

- **Security**: Geofencing validation HARUS di backend
- **GPS**: Requires HTTPS in production
- **Testing**: Gunakan Chrome DevTools untuk simulate GPS
- **Database**: Backup sebelum migrate:fresh

## 🎓 User Workflow

### Daily Flow (Teacher/Staff):
1. Pagi: Check-in via PWA
2. Siang: Lihat status (present/late)
3. Sore: Check-out via PWA
4. Jika lupa: Submit revisi keesokan hari

### Admin Flow:
1. Monitor dashboard statistics
2. Approve pending permits
3. Approve pending revisions
4. Generate monthly reports
5. Manage users

## ✅ Production Checklist

Before deploying to production:

- [ ] Change admin password
- [ ] Setup HTTPS (required for GPS)
- [ ] Configure `.env` for production
- [ ] Set `APP_DEBUG=false`
- [ ] Setup proper database backup
- [ ] Create PWA icons (all sizes)
- [ ] Test geofencing on actual location
- [ ] Setup scheduled jobs (if any)
- [ ] Configure mail driver for notifications

---

**Happy Coding! 🎉**
