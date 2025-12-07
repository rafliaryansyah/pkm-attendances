# 📱 Sistem Absensi SMK - User Guide

<div align="center">

![Status](https://img.shields.io/badge/status-active-success.svg)
![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![Filament](https://img.shields.io/badge/Filament-3.0-orange.svg)

**Sistem Absensi Berbasis GPS dengan Progressive Web App (PWA)**

[Panduan Admin](#-panduan-admin) • [Panduan User](#-panduan-user-guru--staff) • [Instalasi](#-instalasi) • [Troubleshooting](#-troubleshooting)

</div>

---

## 📖 Daftar Isi

- [Tentang Aplikasi](#-tentang-aplikasi)
- [Fitur Utama](#-fitur-utama)
- [Panduan Admin](#-panduan-admin)
- [Panduan User (Guru & Staff)](#-panduan-user-guru--staff)
- [Instalasi](#-instalasi)
- [Troubleshooting](#-troubleshooting)
- [FAQ](#-faq)
- [Dokumentasi Teknis](#-dokumentasi-teknis)

---

## 🎯 Tentang Aplikasi

**Sistem Absensi SMK** adalah aplikasi berbasis web yang dirancang khusus untuk memudahkan proses absensi guru dan staff dengan menggunakan teknologi GPS (Geolocation). Sistem ini memastikan bahwa absensi dilakukan dari lokasi sekolah yang telah ditentukan.

### Keunggulan
- ✅ **GPS-Based Attendance**: Validasi lokasi otomatis menggunakan GPS
- ✅ **Mobile-First Design**: Tampilan responsif optimal di smartphone
- ✅ **Progressive Web App**: Bisa diinstall seperti aplikasi mobile
- ✅ **Real-time Tracking**: Monitoring kehadiran langsung
- ✅ **Approval System**: Sistem persetujuan izin dan revisi
- ✅ **Automated Reports**: Laporan otomatis dengan export Excel

### Teknologi
- **Backend**: Laravel 11 + FilamentPHP 3
- **Frontend**: Livewire 3 + TailwindCSS
- **Database**: MySQL
- **PWA**: Service Worker + Manifest

---

## 🚀 Fitur Utama

### Untuk User (Guru/Staff)
1. **Check-In & Check-Out**
   - Absensi dengan validasi GPS otomatis
   - Status real-time (Hadir/Telat)
   - Riwayat 5 hari terakhir

2. **Pengajuan Izin/Sakit**
   - Form pengajuan dengan upload lampiran
   - Tracking status persetujuan
   - Notifikasi hasil approval

3. **Revisi Absensi**
   - Pengajuan koreksi (lupa check-out, dll)
   - Alasan dan bukti pendukung
   - Approval oleh admin

4. **Profile Management**
   - Update informasi pribadi
   - Lihat jadwal kerja
   - Riwayat absensi lengkap

### Untuk Admin
1. **Dashboard Analytics**
   - Statistik kehadiran hari ini
   - Grafik kehadiran bulanan
   - Status pending approvals

2. **Manajemen User**
   - CRUD data guru/staff
   - Atur jadwal kerja per user
   - Kelola role & permissions

3. **Approval Center**
   - Approve/reject izin & sakit
   - Approve/reject revisi absensi
   - Tambah catatan admin

4. **Reporting & Export**
   - Laporan harian/bulanan
   - Filter by user, tanggal, status
   - Export ke Excel/PDF

5. **Pengaturan Lokasi**
   - Set koordinat GPS sekolah
   - Atur radius toleransi
   - Preview map lokasi

---

## 👨‍💼 Panduan Admin

### 1️⃣ Login ke Admin Dashboard

1. Buka browser dan akses: **`http://127.0.0.1:8000/admin`**
2. Masukkan kredensial admin:
   - **Email**: `admin@smk.sch.id`
   - **Password**: `password`
3. Klik **Login**

> ⚠️ **Penting**: Segera ganti password default setelah login pertama kali!

### 2️⃣ Setup Lokasi Absensi (Wajib Dilakukan Pertama)

**Langkah-langkah:**

1. **Dapatkan Koordinat Sekolah:**
   - Buka [Google Maps](https://www.google.com/maps)
   - Cari lokasi sekolah Anda
   - **Klik kanan** pada titik lokasi sekolah
   - Pilih koordinat yang muncul (contoh: `-6.200000, 106.816666`)
   - Copy koordinat tersebut

2. **Input Koordinat ke Sistem:**
   - Di dashboard admin, buka menu **Pengaturan > Pengaturan Lokasi**
   - Masukkan:
     - **Latitude**: `-6.200000` (sesuaikan dengan lokasi Anda)
     - **Longitude**: `106.816666` (sesuaikan dengan lokasi Anda)
     - **Radius**: `100` meter (disarankan 50-200m)
     - **Nama Lokasi**: `SMK Negeri 1 Jakarta` (opsional)
   - Klik **Simpan Pengaturan**

3. **Verifikasi di Map:**
   - Setelah disimpan, akan muncul preview Google Maps
   - Pastikan pin berada di lokasi yang benar

> 💡 **Tips Radius:**
> - Gedung kecil: 50-100 meter
> - Kompleks/kampus: 100-200 meter
> - Area luas: 200-500 meter

### 3️⃣ Mengelola Data User

**A. Menambah User Baru:**

1. Klik menu **Users** di sidebar
2. Klik tombol **New User** (pojok kanan atas)
3. Isi form:
   - **Nama Lengkap**: `Budi Santoso`
   - **Email**: `budi@smk.sch.id`
   - **Nomor HP**: `081234567890`
   - **Role**: Pilih `Teacher` atau `Staff`
   - **Department**: `TKJ` / `RPL` / `Multimedia`, dll
   - **Jam Kerja**:
     - Mulai: `06:30:00`
     - Selesai: `15:00:00`
   - **Password**: Buat password sementara
4. Klik **Create**

**B. Mengubah Data User:**

1. Klik pada nama user di list
2. Edit field yang diperlukan
3. Klik **Save Changes**

**C. Menghapus User:**

1. Centang checkbox user yang ingin dihapus
2. Pilih **Delete** dari dropdown bulk actions
3. Konfirmasi penghapusan

### 4️⃣ Monitoring Absensi

**Melihat Absensi Hari Ini:**

1. Buka **Dashboard** (halaman utama)
2. Lihat statistik:
   - Jumlah Hadir
   - Jumlah Telat
   - Jumlah Izin/Sakit
   - Jumlah Belum Absen

**Melihat Detail Absensi:**

1. Klik menu **Attendances**
2. Gunakan filter:
   - **Date Range**: Pilih tanggal mulai dan akhir
   - **User**: Filter per user tertentu
   - **Status**: Hadir, Telat, Izin, Sakit, Alpha
3. Klik **Apply Filters**

**Melihat Lokasi Check-in:**

1. Di list Attendances, klik pada record absensi
2. Scroll ke bagian **Location Details**
3. Lihat:
   - Koordinat Check-in
   - Koordinat Check-out
   - Jarak dari lokasi sekolah

### 5️⃣ Approval Izin & Sakit

**Langkah-langkah:**

1. Buka menu **Permits**
2. Filter by **Status**: `Pending`
3. Klik pada pengajuan yang ingin di-review
4. Perhatikan:
   - Alasan izin
   - Tanggal izin
   - Lampiran file (jika ada)
5. Buat keputusan:
   - **Approve**:
     - Klik tab **Approval**
     - Status: `Approved`
     - Admin Notes: (opsional) `Disetujui. Semoga lekas sembuh.`
     - Klik **Save**
   - **Reject**:
     - Klik tab **Approval**
     - Status: `Rejected`
     - Admin Notes: (wajib) `Lampiran surat tidak lengkap. Silakan upload ulang.`
     - Klik **Save**

> 📌 **Catatan**: Setelah approve, sistem akan otomatis membuat record absensi dengan status Izin/Sakit.

### 6️⃣ Approval Revisi Absensi

**Langkah-langkah:**

1. Buka menu **Attendance Revisions**
2. Filter by **Status**: `Pending`
3. Klik pada pengajuan revisi
4. Review:
   - Data absensi lama
   - Waktu check-out yang diajukan
   - Alasan revisi
5. Buat keputusan:
   - **Approve**: Data absensi akan otomatis ter-update
   - **Reject**: Data absensi tetap seperti semula

### 7️⃣ Generate Laporan

**A. Laporan Harian:**

1. Buka menu **Reports > Daily Attendance Report**
2. Pilih **Tanggal**
3. Klik **Generate Report**
4. Klik **Export Excel** untuk download

**B. Laporan Bulanan:**

1. Buka menu **Reports > Monthly Attendance Report**
2. Pilih **Bulan** dan **Tahun**
3. Klik **Generate Report**
4. Laporan akan menampilkan periode 24 bulan lalu - 23 bulan ini
5. Klik **Export Excel** untuk download

**C. Custom Report:**

1. Buka menu **Attendances**
2. Gunakan filter **Date Range** custom
3. Pilih **User** (opsional)
4. Klik tombol **Export** (pojok kanan atas)
5. Pilih format: **Excel** atau **PDF**

### 8️⃣ Pengaturan Banner (Opsional)

1. Buka menu **Banners**
2. Klik **New Banner**
3. Isi:
   - **Title**: Judul banner
   - **Image**: Upload gambar (rasio 16:9)
   - **Order**: Urutan tampil
   - **Is Active**: Centang untuk aktifkan
4. Klik **Create**

> Banner akan muncul di halaman home user sebagai slider.

---

## 👨‍🏫 Panduan User (Guru & Staff)

### 1️⃣ Akses Aplikasi

**Melalui Browser:**

1. Buka browser (Chrome/Firefox direkomendasikan)
2. Akses: **`http://127.0.0.1:8000`**
3. Login dengan kredensial yang diberikan admin:
   - **Email**: email Anda
   - **Password**: password yang diberikan admin
4. Klik **Login**

**Install Sebagai Aplikasi (PWA):**

- **Android (Chrome)**:
  1. Buka website di Chrome
  2. Klik menu ⋮ (titik tiga)
  3. Pilih **"Add to Home screen"** atau **"Install App"**
  4. Konfirmasi instalasi
  
- **iOS (Safari)**:
  1. Buka website di Safari
  2. Tap tombol **Share** (kotak dengan panah ke atas)
  3. Scroll ke bawah dan tap **"Add to Home Screen"**
  4. Tap **"Add"**

### 2️⃣ Memberikan Izin Lokasi (GPS)

> ⚠️ **Wajib**: Aplikasi memerlukan akses GPS untuk validasi lokasi absensi.

**Langkah-langkah:**

1. Saat pertama kali buka aplikasi, browser akan meminta izin lokasi
2. Klik **"Allow"** atau **"Izinkan"**
3. Di status bar aplikasi, Anda akan melihat:
   - 🔄 **"Menunggu lokasi GPS..."** (loading)
   - ✅ **"GPS Aktif - Lokasi terdeteksi"** (sukses, warna hijau)
   - ❌ **"GPS Error - Klik untuk info"** (error, warna merah)

**Jika GPS Error:**

1. **Periksa Pengaturan Browser:**
   - Chrome: Klik ikon 🔒 di address bar → Site settings → Location → Allow
   - Firefox: Klik ikon ⓘ di address bar → Permissions → Location → Allow

2. **Aktifkan Location Services:**
   - **Android**: Settings → Location → On
   - **iOS**: Settings → Privacy → Location Services → On
   - **Windows**: Settings → Privacy → Location → On
   - **Mac**: System Preferences → Security & Privacy → Privacy → Location Services → On

3. **Refresh halaman** setelah mengubah pengaturan

### 3️⃣ Check-In (Absen Masuk)

**Langkah-langkah:**

1. **Pastikan Anda berada di lokasi sekolah**
   - Aplikasi akan otomatis mendeteksi lokasi Anda
   - Indikator GPS harus menunjukkan warna hijau

2. **Klik tombol "Check In"** (tombol hijau besar)

3. **Tunggu proses validasi:**
   - Sistem akan memvalidasi lokasi Anda
   - Jika lokasi valid (dalam radius sekolah):
     - ✅ Muncul notifikasi: **"Clock in berhasil"**
     - Status akan muncul di card "Status Hari Ini"
   - Jika lokasi tidak valid (di luar radius):
     - ❌ Muncul error: **"Anda berada X meter dari lokasi absensi. Jarak maksimal: Y meter"**

4. **Cek Status Kehadiran:**
   - **Hadir** (hijau): Check-in tepat waktu
   - **Telat** (kuning): Check-in setelah jam kerja + toleransi 5 menit

**Contoh:**
- Jam kerja: 06:30
- Toleransi: 5 menit
- Check-in 06:34 → **Hadir**
- Check-in 06:36 → **Telat**

### 4️⃣ Check-Out (Absen Pulang)

**Langkah-langkah:**

1. **Di akhir jam kerja, pastikan Anda masih di lokasi sekolah**

2. **Klik tombol "Check Out"** (tombol biru besar)

3. **Tunggu konfirmasi:**
   - ✅ Muncul notifikasi: **"Clock out berhasil"**
   - Jam check-out akan tercatat

4. **Status berubah menjadi "Absensi Hari Ini Selesai"**
   - Menampilkan jam check-in dan check-out

> 💡 **Tips**: Jika lupa check-out, Anda bisa mengajukan revisi keesokan harinya.

### 5️⃣ Mengajukan Izin / Sakit

**Langkah-langkah:**

1. **Buka menu "Permits"** (di bottom navigation)

2. **Klik tombol "Ajukan Izin"**

3. **Isi formulir:**
   - **Tanggal**: Pilih tanggal izin
   - **Jenis**: Pilih `Izin` atau `Sakit`
   - **Alasan**: Jelaskan alasan (contoh: "Sakit demam tinggi")
   - **Lampiran** (opsional tapi disarankan):
     - Untuk sakit: Upload foto surat dokter
     - Untuk izin: Upload dokumen pendukung
     - Format: JPG, PNG, PDF (max 2MB)

4. **Klik "Submit"**

5. **Tracking Status:**
   - **Pending**: Menunggu persetujuan admin
   - **Approved**: Disetujui (akan otomatis jadi absensi)
   - **Rejected**: Ditolak (baca catatan admin)

6. **Lihat Riwayat:**
   - Semua pengajuan izin dapat dilihat di halaman Permits
   - Filter by status untuk melihat yang pending/approved/rejected

### 6️⃣ Mengajukan Revisi Absensi

**Kapan Perlu Revisi?**
- Lupa check-out
- Check-out terlalu cepat/telat
- Kesalahan data absensi

**Langkah-langkah:**

1. **Buka menu "Revisions"** (di bottom navigation)

2. **Klik tombol "Ajukan Revisi"**

3. **Isi formulir:**
   - **Pilih Absensi**: Pilih data absensi yang ingin direvisi
   - **Waktu Check-out Baru**: Pilih waktu yang seharusnya
   - **Alasan**: Jelaskan (contoh: "Lupa check-out karena rapat mendadak")
   - **Bukti** (opsional): Upload screenshot/foto pendukung

4. **Klik "Submit"**

5. **Menunggu Approval:**
   - Admin akan me-review pengajuan
   - Jika disetujui: Data absensi akan ter-update otomatis
   - Jika ditolak: Lihat catatan admin untuk alasan penolakan

### 7️⃣ Melihat Riwayat Absensi

**Di Halaman Home:**
- Otomatis menampilkan 5 hari terakhir
- Informasi yang ditampilkan:
  - Tanggal
  - Jam check-in dan check-out
  - Status (Hadir/Telat/Izin/Sakit)

**Detail Lengkap:**
- Klik menu **Profile**
- Tab **Riwayat Absensi**
- Filter by tanggal/bulan

### 8️⃣ Update Profile

**Langkah-langkah:**

1. **Buka menu "Profile"**

2. **Tab "Informasi Pribadi":**
   - Edit nama, email, nomor HP
   - Klik **"Update Profile"**

3. **Tab "Ganti Password":**
   - Masukkan password lama
   - Masukkan password baru (min. 8 karakter)
   - Konfirmasi password baru
   - Klik **"Update Password"**

4. **Lihat Jadwal Kerja:**
   - Jam mulai dan jam selesai
   - (Jika perlu diubah, hubungi admin)

---

## 🛠️ Instalasi

> Untuk developer/admin yang akan menginstall aplikasi di server.

### System Requirements

- PHP >= 8.2
- MySQL 8.0+
- Composer
- Node.js & NPM
- Web Server (Apache/Nginx)

### Langkah Instalasi

```bash
# 1. Clone repository
cd /path/to/project

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_smk
DB_USERNAME=root
DB_PASSWORD=

# 5. Migrasi database
php artisan migrate

# 6. Seed data admin
php artisan db:seed --class=AdminUserSeeder

# 7. Link storage
php artisan storage:link

# 8. Build assets
npm run build

# 9. Jalankan server
php artisan serve
```

**Akses Aplikasi:**
- User: `http://localhost:8000`
- Admin: `http://localhost:8000/admin`

**Login Admin Default:**
- Email: `admin@smk.sch.id`
- Password: `password`

> ⚠️ **Penting**: Ganti password default setelah instalasi!

### Production Deployment

**HTTPS Requirement:**
> GPS/Geolocation API memerlukan HTTPS di production. Pastikan SSL certificate sudah terpasang.

**Optimization:**
```bash
# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Build production assets
npm run build
```

**Scheduler (Opsional):**
```bash
# Tambahkan ke crontab untuk scheduled tasks
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🐛 Troubleshooting

### ❌ GPS Tidak Berfungsi

**Penyebab & Solusi:**

1. **Browser tidak support:**
   - ✅ Gunakan Chrome, Firefox, atau Safari terbaru
   - ❌ Hindari browser lama atau tidak terkenal

2. **Permission ditolak:**
   - Klik ikon 🔒/ⓘ di address bar
   - Set Location permission ke "Allow"
   - Refresh halaman

3. **Location Services mati:**
   - **Android**: Settings → Location → On
   - **iOS**: Settings → Privacy → Location Services → On
   - **Mac**: System Preferences → Security & Privacy → Location Services → On
   - **Windows**: Settings → Privacy → Location → On

4. **HTTP vs HTTPS:**
   - Development (localhost): OK dengan HTTP
   - Production: Wajib HTTPS
   - Solusi: Install SSL certificate

5. **GPS accuracy rendah:**
   - Tunggu 10-15 detik untuk GPS stabilize
   - Pindah ke area terbuka (hindari di dalam gedung)
   - Aktifkan WiFi untuk assisted GPS

### ❌ Error "Anda berada di luar area absensi"

**Penyebab & Solusi:**

1. **Koordinat lokasi salah:**
   - Admin: Cek koordinat di Pengaturan Lokasi
   - Pastikan lat/long sesuai Google Maps

2. **Radius terlalu kecil:**
   - Admin: Perbesar radius (misal dari 50m ke 100m)
   - Sesuaikan dengan luas area sekolah

3. **GPS drift:**
   - Tunggu beberapa detik untuk GPS accuracy meningkat
   - Coba di lokasi berbeda dalam area sekolah

### ❌ Tombol Check-In/Out Tidak Muncul

**Penyebab & Solusi:**

1. **Sudah absen hari ini:**
   - Cek status di card "Status Hari Ini"
   - Jika sudah check-in, yang muncul tombol check-out
   - Jika sudah check-out, tidak ada tombol (sudah selesai)

2. **Session expired:**
   - Logout dan login kembali
   - Clear browser cache

3. **Livewire error:**
   ```bash
   # Developer: Publish Livewire assets
   php artisan livewire:publish --assets
   php artisan optimize:clear
   ```

### ❌ Upload File Gagal

**Penyebab & Solusi:**

1. **File terlalu besar:**
   - Max size: 2MB
   - Compress gambar sebelum upload

2. **Format tidak didukung:**
   - Allowed: JPG, PNG, PDF
   - Convert file ke format yang didukung

3. **Permission error:**
   ```bash
   # Developer: Fix storage permissions
   chmod -R 775 storage
   chown -R www-data:www-data storage
   ```

### ❌ Error 500 / White Screen

**Solusi untuk Developer:**

```bash
# Clear all cache
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Re-cache
php artisan config:cache
php artisan route:cache

# Check logs
tail -f storage/logs/laravel.log
```

### ❌ Admin Login Gagal

**Penyebab & Solusi:**

1. **Belum seed admin:**
   ```bash
   php artisan db:seed --class=AdminUserSeeder
   ```

2. **Password lupa:**
   ```bash
   php artisan tinker
   >>> $admin = User::where('email', 'admin@smk.sch.id')->first();
   >>> $admin->password = Hash::make('password_baru');
   >>> $admin->save();
   ```

3. **Session issue:**
   - Clear browser cookies
   - Coba incognito/private mode

---

## ❓ FAQ

### 1. Apakah harus selalu di lokasi sekolah untuk absen?

**Ya, sistem menggunakan GPS untuk memastikan Anda berada di area sekolah saat absen.** Ini mencegah kecurangan absensi dari lokasi lain.

### 2. Berapa radius maksimal dari lokasi sekolah?

**Defaultnya 100 meter, tapi admin bisa mengatur sesuai kebutuhan.** Disarankan 50-200 meter tergantung luas area sekolah.

### 3. Bagaimana jika lupa check-out?

**Ajukan revisi absensi keesokan harinya:**
1. Buka menu **Revisions**
2. Pilih data absensi yang lupa check-out
3. Isi waktu check-out yang seharusnya
4. Berikan alasan
5. Tunggu approval admin

### 4. Apakah bisa absen untuk orang lain?

**Tidak, sistem merekam koordinat GPS setiap absensi.** Admin bisa mengecek lokasi check-in/out untuk memastikan validitas.

### 5. Apa bedanya Izin dan Sakit?

- **Izin**: Untuk keperluan non-medis (urusan keluarga, acara penting, dll)
- **Sakit**: Khusus untuk alasan kesehatan (wajib lampirkan surat dokter)

### 6. Berapa lama approval izin/revisi diproses?

**Tergantung admin, biasanya 1-2 hari kerja.** Untuk urgent, hubungi admin langsung via WhatsApp/telepon.

### 7. Apakah aplikasi bisa offline?

**Tidak, aplikasi memerlukan koneksi internet** untuk:
- Validasi GPS ke server
- Menyimpan data absensi
- Sync data real-time

### 8. Bagaimana cara install aplikasi di HP?

**Lihat panduan [Akses Aplikasi](#1️⃣-akses-aplikasi)** di atas untuk cara install PWA di Android dan iOS.

### 9. Apakah data lokasi saya disimpan?

**Ya, untuk keperluan audit dan validasi.** Data koordinat GPS hanya disimpan saat check-in/out dan hanya bisa diakses oleh admin.

### 10. Toleransi keterlambatan berapa menit?

**Default 5 menit setelah jam kerja.** Contoh:
- Jam kerja: 06:30
- Toleransi: 5 menit
- Check-in sampai 06:35 → Hadir
- Check-in 06:36+ → Telat

---

## 📚 Dokumentasi Teknis

Untuk dokumentasi teknis lengkap, lihat file-file berikut:

- **[README_SISTEM_ABSENSI.md](README_SISTEM_ABSENSI.md)** - Dokumentasi sistem lengkap
- **[API_REFERENCE.md](API_REFERENCE.md)** - Dokumentasi API endpoints
- **[LOCATION_SETUP_GUIDE.md](LOCATION_SETUP_GUIDE.md)** - Panduan setup lokasi GPS
- **[QUICK_START.md](QUICK_START.md)** - Quick start untuk developer
- **[REQUIRMENT.md](REQUIRMENT.md)** - Requirements & specifications

---

## 📞 Support & Kontak

Untuk bantuan atau pertanyaan:

1. **Admin Sekolah**: Hubungi bagian IT/Admin sistem
2. **Developer**: Buat issue di repository atau hubungi tim developer

---

## 📝 Changelog

### Version 1.0.0 (2025-12-07)
- ✅ GPS-based attendance system
- ✅ Admin dashboard dengan FilamentPHP
- ✅ PWA mobile interface
- ✅ Approval system untuk izin & revisi
- ✅ Reporting & export Excel
- ✅ Location settings dengan preview map
- ✅ Banner slider untuk announcements
- ✅ Comprehensive error handling

---

## 📄 License

This project is private and proprietary. All rights reserved.

**Developed with ❤️ for SMK**

---

<div align="center">

Made with [Laravel](https://laravel.com) • [FilamentPHP](https://filamentphp.com) • [Livewire](https://livewire.laravel.com)

**© 2025 PKM Absensi System**

</div>
