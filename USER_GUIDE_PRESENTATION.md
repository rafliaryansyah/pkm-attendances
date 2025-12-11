# 📱 User Guide: Sistem Absensi SMK
**Panduan Presentasi untuk Google Slides**

---

# SECTION 1: PENGENALAN APLIKASI

---

## Slide 1: Cover / Judul
**SISTEM ABSENSI SMK**
**Berbasis GPS dengan Progressive Web App (PWA)**

*Sistem Absensi Modern untuk Guru dan Staff*

---

## Slide 2: Apa itu Sistem Absensi SMK?

### Definisi
Aplikasi absensi berbasis web yang menggunakan teknologi **GPS (Geolocation)** untuk memvalidasi kehadiran guru dan staff di lokasi sekolah secara real-time.

### Tujuan Utama
- ✅ Meningkatkan akurasi dan transparansi absensi
- ✅ Mencegah kecurangan absensi dari lokasi lain
- ✅ Mempermudah monitoring kehadiran secara real-time
- ✅ Mengotomatisasi proses approval izin dan revisi
- ✅ Menyediakan laporan kehadiran yang akurat

---

## Slide 3: Mengapa Menggunakan GPS?

### Keunggulan GPS-Based Attendance

**1. Validasi Lokasi Otomatis**
- Sistem memastikan pengguna berada di area sekolah saat absen
- Radius validasi dapat diatur (default 80-100 meter)

**2. Anti-Fraud (Mencegah Kecurangan)**
- Tidak bisa absen dari rumah atau lokasi lain
- Koordinat GPS tersimpan untuk audit trail

**3. Real-time Tracking**
- Admin dapat memantau kehadiran langsung
- Notifikasi keterlambatan otomatis

**4. Akurat & Transparan**
- Data lokasi tidak dapat dimanipulasi
- Riwayat absensi tercatat dengan lengkap

---

## Slide 4: Teknologi yang Digunakan

### Tech Stack

**Backend:**
- 🔧 Laravel 11 - Framework PHP modern
- 🎨 FilamentPHP 3 - Admin dashboard elegant
- ⚡ Livewire 3 - Reactive components tanpa JavaScript kompleks

**Frontend:**
- 💅 TailwindCSS - Modern UI framework
- 📱 Responsive Design - Optimal di semua device
- 🚀 PWA (Progressive Web App) - Install seperti aplikasi native

**Database:**
- 🗄️ MySQL - Database relational yang robust

**Fitur Khusus:**
- 🌍 Geolocation API - GPS tracking
- 🔄 Service Worker - Offline support & caching
- 📊 Export Excel - Laporan otomatis

---

## Slide 5: Keunggulan Aplikasi

### 🌟 Benefit untuk Sekolah

**Untuk Guru & Staff:**
- ✅ Proses absensi cepat (< 10 detik)
- ✅ Interface mudah digunakan
- ✅ Riwayat absensi tersimpan
- ✅ Pengajuan izin/revisi online
- ✅ Bisa diakses via HP (PWA)

**Untuk Admin:**
- ✅ Dashboard analytics real-time
- ✅ Approval system terpusat
- ✅ Laporan otomatis & export Excel
- ✅ Monitoring lokasi absensi
- ✅ Manajemen user mudah

**Untuk Sekolah:**
- ✅ Data akurat untuk evaluasi kinerja
- ✅ Transparansi & accountability
- ✅ Efisiensi operasional
- ✅ Paperless & eco-friendly
- ✅ Hemat biaya (no hardware khusus)

---

## Slide 6: Target Pengguna

### Siapa yang Menggunakan Aplikasi Ini?

**1. Guru (Teacher)**
- Melakukan check-in/out harian
- Mengajukan izin/sakit
- Melihat riwayat absensi

**2. Staff Administrasi**
- Sama seperti guru
- Check-in/out dengan validasi GPS

**3. Admin Sistem**
- Mengelola data user
- Approve/reject izin & revisi
- Generate laporan
- Setup pengaturan lokasi

---

## Slide 7: Alur Kerja Sistem (Workflow)

### User Workflow
```
📱 User Login
    ↓
🌍 GPS Terdeteksi
    ↓
✅ Check-In (di lokasi sekolah)
    ↓
🏫 Bekerja Seharian
    ↓
✅ Check-Out (di lokasi sekolah)
    ↓
📊 Data Tersimpan Otomatis
```

### Admin Workflow
```
💻 Admin Login
    ↓
📍 Setup Lokasi GPS (sekali)
    ↓
👥 Tambah Data User
    ↓
📊 Monitor Dashboard Real-time
    ↓
✔️ Approve Izin/Revisi
    ↓
📈 Generate Laporan Bulanan
```

---

## Slide 8: Persyaratan Sistem

### Apa yang Dibutuhkan?

**Untuk User (Guru/Staff):**
- 📱 Smartphone dengan GPS aktif
- 🌐 Koneksi internet (3G/4G/WiFi)
- 🔍 Browser modern:
  - Chrome (recommended)
  - Firefox
  - Safari (iOS)
- 📍 Izin akses lokasi di browser

**Untuk Admin:**
- 💻 Laptop/PC dengan browser modern
- 🌐 Koneksi internet stabil
- 🖱️ Mouse & keyboard

**Infrastruktur Server:**
- ☁️ Web server (Apache/Nginx)
- 🗄️ Database MySQL
- 🔐 SSL Certificate (HTTPS untuk production)

---

# SECTION 2: APLIKASI USER (FITUR-FITUR)

---

## Slide 9: Cara Akses Aplikasi

### Login Pertama Kali

**1. Buka Browser**
- Akses: `https://your-school-attendance.app`
- Atau: `https://ip-address:8000` (local development)

**2. Input Kredensial**
- Email: Diberikan oleh admin
- Password: Diberikan oleh admin (wajib diganti setelah login pertama)

**3. Izinkan Akses GPS**
- Browser akan minta izin lokasi
- Klik "Allow" / "Izinkan"
- GPS indicator akan muncul (warna hijau = aktif)

---

## Slide 10: Install Aplikasi PWA

### Progressive Web App (Install seperti App Native)

**Android (Chrome):**
1. Buka website di Chrome
2. Tap menu ⋮ (3 titik)
3. Pilih **"Add to Home screen"**
4. Beri nama: "Absensi SMK"
5. Tap **"Add"**
6. Icon muncul di home screen

**iOS (Safari):**
1. Buka website di Safari
2. Tap tombol **Share** 📤
3. Scroll ke bawah
4. Tap **"Add to Home Screen"**
5. Beri nama: "Absensi SMK"
6. Tap **"Add"**

**Keuntungan PWA:**
- ✅ Buka langsung dari home screen
- ✅ Fullscreen (tanpa address bar)
- ✅ Faster loading (caching)
- ✅ Terlihat seperti app native

---

## Slide 11: Halaman Home (Dashboard User)

### Tampilan Utama

**Komponen:**

**1. Banner Slider** (top)
- Info penting dari sekolah
- Pengumuman event
- Slideshow otomatis

**2. Status GPS**
- 🟢 GPS Aktif - Lokasi terdeteksi
- 🔴 GPS Error - Butuh troubleshoot
- 🟡 Menunggu GPS...

**3. Card "Status Hari Ini"**
- Belum absen: Tombol "Check In" (hijau)
- Sudah check-in: Menampilkan jam masuk + tombol "Check Out" (biru)
- Sudah check-out: Menampilkan jam masuk & pulang (abu-abu)

**4. Riwayat 5 Hari Terakhir**
- Tanggal
- Jam masuk & pulang
- Status badge (Hadir/Telat/Izin/Sakit)

**5. Bottom Navigation**
- 🏠 Home
- 📝 Permits (Izin)
- ✏️ Revisions (Revisi)
- 👤 Profile

---

## Slide 12: Fitur 1 - Check-In (Absen Masuk)

### Cara Check-In

**Langkah:**
1. **Pastikan GPS aktif** (indikator hijau)
2. **Pastikan Anda di lokasi sekolah** (dalam radius yang ditentukan)
3. Tap tombol **"Check In"** (tombol hijau besar)
4. Tunggu loading (± 2-5 detik)
5. Muncul notifikasi sukses ✅

**Validasi Otomatis:**
- ✅ Lokasi valid → Check-in berhasil
- ❌ Lokasi di luar radius → Error dengan jarak aktual
- ❌ GPS mati → Peringatan "GPS tidak aktif"

**Status Kehadiran:**
- 🟢 **Hadir** - Check-in tepat waktu (sebelum jam kerja + 5 menit)
- 🟡 **Telat** - Check-in setelah jam kerja + 5 menit

**Contoh:**
- Jam kerja: 06:30
- Toleransi: 5 menit
- Check-in 06:34 → Hadir ✅
- Check-in 06:36 → Telat ⚠️

---

## Slide 13: Fitur 2 - Check-Out (Absen Pulang)

### Cara Check-Out

**Langkah:**
1. **Di akhir jam kerja, pastikan masih di lokasi sekolah**
2. Tap tombol **"Check Out"** (tombol biru besar)
3. Tunggu loading (± 2-5 detik)
4. Muncul notifikasi sukses ✅

**Validasi:**
- ✅ Harus sudah check-in hari ini
- ✅ Belum check-out sebelumnya
- ✅ Lokasi dalam radius sekolah
- ❌ Lokasi di luar radius → Error

**Setelah Check-Out:**
- Status berubah: "Absensi Hari Ini Selesai"
- Menampilkan jam check-in dan check-out
- Tombol check-in/out hilang
- Data tersimpan permanent

**Catatan:**
💡 Jika lupa check-out, bisa ajukan revisi keesokan harinya

---

## Slide 14: Fitur 3 - Pengajuan Izin/Sakit

### Kapan Menggunakan Fitur Ini?

- 🏥 **Sakit** - Tidak bisa masuk karena alasan kesehatan
- 📋 **Izin** - Keperluan lain (keluarga, urusan penting, dll)

### Cara Mengajukan:

**1. Buka Menu "Permits"**
- Tap icon 📝 Permits di bottom navigation

**2. Tap "Ajukan Izin"** (tombol biru, pojok kanan atas)

**3. Isi Formulir:**
- **Tanggal**: Pilih tanggal izin (bisa single atau range)
- **Jenis**: Pilih "Sakit" atau "Izin"
- **Alasan**: Tulis penjelasan (min. 10 karakter)
- **Lampiran** (opsional, tapi disarankan):
  - Sakit: Foto surat dokter
  - Izin: Dokumen pendukung
  - Format: JPG, PNG, PDF (max 2MB)

**4. Tap "Submit"**

**5. Tunggu Approval Admin**
- Status: **Pending** (⏳ menunggu)
- Status: **Approved** (✅ disetujui)
- Status: **Rejected** (❌ ditolak)

---

## Slide 15: Tracking Status Izin

### Melihat Status Pengajuan

**Di Halaman Permits:**

**Filter by Status:**
- **All** - Semua pengajuan
- **Pending** - Menunggu approval
- **Approved** - Disetujui
- **Rejected** - Ditolak

**Informasi di Card:**
- Tanggal pengajuan
- Jenis (Sakit/Izin)
- Tanggal izin (from - to)
- Status badge (warna berbeda per status)
- Alasan singkat

**Detail Lengkap:**
- Tap card untuk lihat detail
- Lihat alasan lengkap
- Lihat lampiran (jika ada)
- Lihat catatan admin (jika ada)

**Notifikasi:**
- 🔔 Akan ada notifikasi saat admin approve/reject

---

## Slide 16: Fitur 4 - Pengajuan Revisi Absensi

### Kapan Butuh Revisi?

**Situasi:**
- 😅 Lupa check-out pulang
- ⏰ Check-out terlalu cepat/telat (kesalahan)
- 🐛 Data absensi error/salah
- 📱 HP mati saat check-out

### Cara Mengajukan Revisi:

**1. Buka Menu "Revisions"**
- Tap icon ✏️ Revisions di bottom navigation

**2. Tap "Ajukan Revisi"** (tombol biru)

**3. Isi Formulir:**
- **Pilih Absensi**: Dropdown absensi yang mau direvisi
- **Waktu Check-out Baru**: Pilih jam yang seharusnya
- **Alasan**: Jelaskan kenapa perlu revisi (min. 10 karakter)
  - Contoh: "Lupa check-out karena rapat mendadak"
- **Bukti** (opsional): Upload screenshot/foto pendukung

**4. Tap "Submit"**

**5. Tunggu Approval:**
- **Pending** → Admin sedang review
- **Approved** → Data absensi otomatis ter-update ✅
- **Rejected** → Data tetap seperti semula ❌

---

## Slide 17: Fitur 5 - Riwayat Absensi

### Melihat Riwayat Lengkap

**Di Halaman Home:**
- Otomatis menampilkan **5 hari terakhir**
- Quick view status harian

**Di Halaman Profile:**
1. Tap menu **Profile** (icon 👤)
2. Tab **"Riwayat Absensi"**
3. Lihat semua data absensi

**Informasi yang Ditampilkan:**
- 📅 Tanggal
- 🕐 Jam check-in
- 🕔 Jam check-out
- 🎯 Status (Hadir/Telat/Izin/Sakit/Alpha)
- 📍 Badge "Revisi" (jika data pernah direvisi)

**Filter & Search:**
- Filter by bulan
- Filter by status
- Search by tanggal

**Export Personal:**
- Download riwayat absensi sendiri
- Format: Excel (CSV)
- Untuk keperluan pribadi/arsip

---

## Slide 18: Fitur 6 - Manajemen Profile

### Update Informasi Pribadi

**Akses:** Tap menu **Profile** → Tab **"Informasi Pribadi"**

**Yang Bisa Diubah:**
- 👤 Nama lengkap
- 📧 Email
- 📱 Nomor HP/WhatsApp

**Yang Tidak Bisa Diubah (by User):**
- 🏢 Department (diatur admin)
- 🕐 Jam kerja (diatur admin)
- 🔐 Role (admin/teacher/staff)

**Cara Update:**
1. Edit field yang ingin diubah
2. Tap **"Update Profile"**
3. Konfirmasi perubahan

---

## Slide 19: Fitur 7 - Ganti Password

### Keamanan Akun

**Akses:** Profile → Tab **"Ganti Password"**

**Langkah:**
1. Masukkan **Password Lama**
2. Masukkan **Password Baru** (min. 8 karakter)
3. **Konfirmasi Password Baru** (harus sama)
4. Tap **"Update Password"**
5. Muncul notifikasi sukses ✅
6. Logout otomatis (login ulang dengan password baru)

**Tips Password Kuat:**
- Min. 8 karakter
- Kombinasi huruf besar & kecil
- Angka
- Karakter special (@, !, #, dll)

**Contoh:**
- ❌ Lemah: `password123`
- ✅ Kuat: `Sch00l@2025!`

---

## Slide 20: Tips & Best Practices untuk User

### 💡 Tips Penggunaan Optimal

**GPS & Lokasi:**
- ✅ Aktifkan GPS sebelum buka aplikasi
- ✅ Pastikan di area terbuka untuk akurasi GPS lebih baik
- ✅ Tunggu 5-10 detik sampai GPS stabil (indikator hijau)
- ❌ Jangan check-in di dalam basement/parkir bawah tanah

**Absensi Harian:**
- ✅ Check-in segera saat tiba di sekolah
- ✅ Check-out sebelum pulang (jangan lupa!)
- ✅ Cek riwayat absensi secara berkala
- ⏰ Datang tepat waktu (hindari status "Telat")

**Pengajuan Izin:**
- ✅ Ajukan izin sedini mungkin (H-1 jika bisa)
- ✅ Lampirkan dokumen pendukung
- ✅ Tulis alasan yang jelas dan detail
- 📧 Followup via email/WA jika urgent

**Keamanan:**
- ✅ Ganti password default setelah login pertama
- ✅ Jangan share password ke orang lain
- ✅ Logout setelah selesai (jika di device umum)
- 🔐 Gunakan password yang kuat

---

# SECTION 3: APLIKASI ADMIN

---

## Slide 21: Akses Admin Dashboard

### Login Admin Panel

**URL Admin:**
- Production: `https://your-school.app/admin`
- Local: `http://localhost:8000/admin`

**Kredensial Default:**
- Email: `admin@smk.sch.id`
- Password: `password`

⚠️ **WAJIB ganti password setelah login pertama!**

**Tampilan Dashboard:**
- Sidebar navigation (menu utama)
- Header (profile & notifications)
- Content area (konten dinamis)
- Widgets analytics (statistik real-time)

---

## Slide 22: Dashboard Analytics

### Overview Halaman Utama

**Widgets Statistik Hari Ini:**

**1. Total Kehadiran**
- 🟢 Hadir: XX orang
- 🟡 Telat: XX orang
- 🔵 Izin: XX orang
- 🟠 Sakit: XX orang
- 🔴 Alpha: XX orang

**2. Chart Kehadiran Bulanan**
- Grafik line/bar
- Tren kehadiran 30 hari terakhir
- Breakdown per status

**3. Pending Approvals**
- ⏳ Izin menunggu: XX
- ⏳ Revisi menunggu: XX
- Quick action button ke approval page

**4. Recent Activities**
- Check-in/out terbaru
- Approval terbaru
- Log aktivitas sistem

---

## Slide 23: Fitur Admin 1 - Setup Lokasi GPS (Mandatory)

### Pengaturan Lokasi Absensi (Wajib Pertama Kali)

**Menu:** Dashboard → **"Location Settings"**

### Langkah Setup:

**1. Dapatkan Koordinat Sekolah:**
- Buka [Google Maps](https://www.google.com/maps)
- Cari lokasi sekolah
- **Klik kanan** pada titik lokasi sekolah
- Copy koordinat (format: `-6.154928, 106.772240`)

**2. Input ke Sistem:**
- **Latitude**: `-6.154928` (contoh, sesuaikan)
- **Longitude**: `106.772240` (contoh, sesuaikan)
- **Radius**: `80-100` meter (recommended)
- **Nama Lokasi**: `SMK Negeri 1 Jakarta` (opsional)
- **Alamat**: (opsional, untuk referensi)

**3. Preview Map:**
- Setelah save, muncul Google Maps preview
- Marker merah = titik pusat sekolah
- Circle biru = radius validasi
- Pastikan circle mencakup area sekolah

**4. Save & Test:**
- Klik **"Save Settings"**
- Test dengan check-in dari HP di lokasi

---

## Slide 24: Tips Setup Radius yang Tepat

### Menentukan Radius Optimal

**Faktor yang Perlu Dipertimbangkan:**

**1. Luas Area Sekolah:**
- Gedung kecil (1-2 lantai): 50-80 meter
- Kompleks sedang: 80-150 meter
- Kampus/area luas: 150-300 meter

**2. Akurasi GPS:**
- GPS outdoor: ±5-10 meter
- GPS indoor (near window): ±10-20 meter
- GPS indoor (no window): ±20-50 meter

**3. Best Practices:**
- **Jangan terlalu kecil**: Banyak false negative (user valid tapi ditolak)
- **Jangan terlalu besar**: Bisa absen dari luar area (false positive)
- **Recommended**: 100 meter untuk sebagian besar SMK
- **Testing**: Uji dengan berjalan di tepi area sekolah

**Contoh Real:**
- Sekolah kecil (10x20m): Radius 50m ✅
- SMK dengan lapangan: Radius 100m ✅
- Kompleks SMK multi-gedung: Radius 150-200m ✅

---

## Slide 25: Fitur Admin 2 - Manajemen User

### CRUD User (Create, Read, Update, Delete)

**Menu:** Dashboard → **"Users"**

### A. Menambah User Baru:

**1. Tap "New User"** (tombol pojok kanan atas)

**2. Isi Form:**
- **Nama Lengkap**: `Budi Santoso, S.Pd`
- **Email**: `budi.santoso@smk.sch.id` (unique)
- **Nomor HP**: `081234567890` (unique, format Indonesia)
- **Password**: Buat password sementara (user wajib ganti)
- **Role**: Pilih dropdown:
  - `admin` - Akses admin panel
  - `teacher` - Guru (akses user app)
  - `staff` - Staff administrasi (akses user app)
- **Department**: `TKJ` / `RPL` / `Multimedia` / `Admin`, dll
- **Jam Kerja:**
  - Work Start: `06:30:00`
  - Work End: `15:00:00`

**3. Tap "Create"**

**4. Informasikan Kredensial:**
- Share email & password ke user via WA/email
- Instruksikan untuk ganti password setelah login pertama

---

## Slide 26: Manajemen User (Lanjutan)

### B. Edit Data User:

**Langkah:**
1. Di list users, **tap nama user**
2. Edit field yang perlu diubah
3. **Save Changes**

**Yang Sering Diubah:**
- Jam kerja (jika ada perubahan shift)
- Department (jika pindah)
- Email/HP (jika berubah)
- Role (jika promosi ke admin)

### C. Hapus User:

**Single Delete:**
1. Tap user → **Delete** button
2. Konfirmasi penghapusan

**Bulk Delete:**
1. Centang checkbox user yang mau dihapus (bisa multiple)
2. Dropdown **"Bulk Actions"** → **"Delete"**
3. Konfirmasi

⚠️ **Peringatan:** Data absensi user juga terhapus (irreversible!)

### D. Search & Filter:

**Search:**
- Search by nama, email, atau HP
- Real-time search

**Filter:**
- Filter by role (admin/teacher/staff)
- Filter by department
- Filter active/inactive

---

## Slide 27: Fitur Admin 3 - Monitoring Absensi

### Melihat Data Absensi Real-time

**Menu:** Dashboard → **"Attendances"**

### Fitur Monitoring:

**1. List View:**
- Tabel absensi semua user
- Columns:
  - User (nama)
  - Date (tanggal)
  - Clock In (jam masuk)
  - Clock Out (jam pulang)
  - Status (badge warna)
  - Location (koordinat)

**2. Filter Options:**
- **Date Range**: Pilih tanggal from - to
- **User**: Filter per user tertentu (dropdown)
- **Status**: Hadir/Telat/Izin/Sakit/Alpha
- **Department**: Filter per department

**3. Detail View:**
- Tap row untuk lihat detail
- Informasi lengkap:
  - User info
  - Timestamp check-in/out
  - **Koordinat GPS** (lat/long)
  - **Jarak dari lokasi** (meter)
  - Status badge
  - Notes (jika ada)
  - Is Revision? (badge jika data pernah direvisi)

---

## Slide 28: Validasi Lokasi Absensi

### Mengecek Keabsahan Lokasi

**Mengapa Penting?**
- Memastikan user benar-benar di sekolah saat absen
- Deteksi potential fraud/cheating

**Cara Cek:**

**1. Buka Detail Attendance:**
- Tap row absensi yang ingin dicek

**2. Lihat Section "Location Details":**
- **Check-In Location:**
  - Latitude: `-6.154928`
  - Longitude: `106.772240`
  - Distance from target: `45 meters` ✅
- **Check-Out Location:**
  - Latitude: `-6.154930`
  - Longitude: `106.772245`
  - Distance from target: `48 meters` ✅

**3. Red Flags (Suspicious):**
- ❌ Distance > radius (tapi tetap lolos sistem)
  - *Seharusnya tidak terjadi, cek bug*
- ❌ Koordinat check-in & check-out sangat berbeda jauh
  - *User mungkin check-out dari lokasi lain (tidak valid)*
- ❌ Koordinat di luar area sekolah
  - *GPS spoof / fake location (cheating)*

**4. Action jika Ada Fraud:**
- Hubungi user untuk klarifikasi
- Jika terbukti curang:
  - Ubah status jadi "Alpha"
  - Beri warning/sanksi sesuai aturan

---

## Slide 29: Fitur Admin 4 - Approval Izin/Sakit

### Proses Persetujuan Permit

**Menu:** Dashboard → **"Permits"**

### Workflow Approval:

**1. Filter Pending:**
- Tap filter **"Status"** → **"Pending"**
- Muncul list semua pengajuan yang menunggu

**2. Review Pengajuan:**
- Tap card/row permit
- Lihat detail:
  - User (nama & department)
  - Tanggal izin (from - to)
  - Jenis (Sakit/Izin)
  - Alasan lengkap
  - Lampiran (jika ada - download/view)
  - Tanggal pengajuan

**3. Buat Keputusan:**

**Opsi A: APPROVE (Setujui)**
- Scroll ke section **"Approval"**
- Status: Pilih **"Approved"**
- Admin Notes (opsional):
  - `"Disetujui. Semoga lekas sembuh."` (untuk sakit)
  - `"Disetujui."` (untuk izin)
- Tap **"Save"**
- 🎉 Sistem otomatis:
  - Update status permit → Approved
  - **Auto-create attendance** dengan status Izin/Sakit
  - Notifikasi ke user

---

## Slide 30: Approval Izin (Lanjutan)

**Opsi B: REJECT (Tolak)**
- Status: Pilih **"Rejected"**
- Admin Notes (**WAJIB isi**):
  - `"Lampiran surat dokter tidak jelas. Mohon upload ulang."`
  - `"Tanggal bentrok dengan event sekolah. Tidak bisa disetujui."`
- Tap **"Save"**
- 📧 Sistem:
  - Update status → Rejected
  - Notifikasi ke user dengan catatan admin
  - User bisa ajukan ulang (jika perlu)

### Tips Approval:

**✅ Best Practices:**
- Review lampiran dengan teliti
- Cek tanggal (hindari overlap permit)
- Beri catatan yang konstruktif saat reject
- Response maksimal 1-2 hari kerja

**📋 Kriteria Approval:**
- Sakit: **WAJIB** ada surat dokter
- Izin: Alasan harus jelas & reasonable
- Dokumen pendukung lengkap
- Tidak terlalu sering (cek history user)

---

## Slide 31: Fitur Admin 5 - Approval Revisi Absensi

### Proses Persetujuan Revision

**Menu:** Dashboard → **"Attendance Revisions"**

### Workflow Approval:

**1. Filter Pending:**
- Status filter → **"Pending"**

**2. Review Detail:**
- Tap row revision request
- Lihat informasi:
  - **User** (nama & department)
  - **Tanggal** absensi yang mau direvisi
  - **Data Lama:**
    - Clock In: `06:35:00`
    - Clock Out: `--:--:--` (lupa check-out)
  - **Data Baru (Proposed):**
    - Clock In: `06:35:00` (tidak berubah)
    - Clock Out: `15:10:00` (usulan baru)
  - **Alasan Revisi:**
    - `"Lupa check-out karena rapat mendadak dengan kepala sekolah"`
  - **Bukti Pendukung:** (jika ada)

**3. Validasi:**
- Cek kebenaran alasan
- Cek bukti pendukung (screenshot, foto, dll)
- Cross-check dengan schedule rapat (jika applicable)
- Lihat track record user (apakah sering lupa?)

---

## Slide 32: Approval Revisi (Lanjutan)

**4. Buat Keputusan:**

**Opsi A: APPROVE**
- Status: **"Approved"**
- Admin Note (opsional): `"Revisi disetujui."`
- Tap **"Save"**
- 🎉 **Sistem Otomatis:**
  - Update data absensi sesuai proposed time
  - Flag `is_revision = true`
  - Notifikasi ke user

**Opsi B: REJECT**
- Status: **"Rejected"**
- Admin Note (**wajib**):
  - `"Alasan tidak cukup kuat. Silakan lebih teliti kedepannya."`
  - `"Bukti tidak cukup. Mohon lampirkan bukti rapat/email konfirmasi."`
- Tap **"Save"**
- Data absensi tetap seperti semula (tidak berubah)

### Tips Approval Revisi:

**✅ Approve jika:**
- Alasan masuk akal & jelas
- Ada bukti pendukung
- Waktu proposed reasonable (sesuai jam kerja)
- Tidak terlalu sering request revisi

**❌ Reject jika:**
- Alasan tidak jelas/mengada-ada
- Tidak ada bukti
- Terlalu sering lupa (indikasi kurang disiplin)
- Waktu proposed tidak masuk akal

---

## Slide 33: Fitur Admin 6 - Generate Laporan

### Reporting & Export

**Menu:** Dashboard → **"Reports"**

### A. Laporan Harian (Daily Report):

**Akses:** Reports → **"Daily Attendance Report"**

**Langkah:**
1. Pilih **Tanggal** (date picker)
2. Tap **"Generate Report"**
3. Lihat tabel:
   - List semua user
   - Status absensi hari itu
   - Jam masuk & pulang
   - Keterangan
4. Tap **"Export Excel"** untuk download
   - Format: `.xlsx`
   - Nama file: `Daily_Report_YYYY-MM-DD.xlsx`

**Use Case:**
- Rekap harian untuk kepala sekolah
- Monitoring kehadiran hari ini
- Absensi untuk payroll harian

---

## Slide 34: Laporan Bulanan

### B. Laporan Bulanan (Monthly Report):

**Akses:** Reports → **"Monthly Attendance Report"**

**Periode Khusus:**
- Sistem menggunakan periode **24th to 23rd**
- Contoh: Pilih **Desember 2025**
  - Data: **24 Nov 2025** s/d **23 Des 2025**
- Sesuai periode gaji/payroll di banyak sekolah

**Langkah:**
1. Pilih **Bulan** (month picker)
2. Pilih **Tahun** (year picker)
3. Tap **"Generate Report"**
4. Lihat summary:
   - Per user: Total hadir, telat, izin, sakit, alpha
   - Attendance rate (%)
   - Total hari kerja vs total kehadiran
5. Tap **"Export Excel"**

**Use Case:**
- Laporan bulanan ke dinas pendidikan
- Evaluasi kinerja guru/staff
- Data payroll & tunjangan kehadiran
- Arsip dokumentasi

---

## Slide 35: Laporan Custom

### C. Custom Report (Advanced):

**Akses:** Attendances → Filter → Export

**Langkah:**
1. **Set Date Range:**
   - From: `2025-01-01`
   - To: `2025-01-31`
2. **Filter by User** (opsional):
   - Pilih user tertentu
   - Atau kosongkan untuk semua user
3. **Filter by Status** (opsional):
   - Hadir, Telat, Izin, Sakit, Alpha
4. **Filter by Department** (opsional):
   - TKJ, RPL, Multimedia, dll
5. **Tap "Apply Filters"**
6. **Tap "Export"** (pojok kanan atas)
7. **Pilih Format:**
   - Excel (`.xlsx`)
   - PDF (untuk print)

**Use Case:**
- Laporan custom per department
- Audit trail per user
- Analisis tren keterlambatan
- Report untuk rapat evaluasi

---

## Slide 36: Fitur Admin 7 - Manajemen Banner

### Banner Slider untuk Pengumuman

**Menu:** Dashboard → **"Banners"**

**Fungsi:**
- Menampilkan banner di home page user
- Untuk pengumuman, info event, reminder
- Slideshow otomatis

### CRUD Banner:

**A. Create Banner:**
1. Tap **"New Banner"**
2. Isi form:
   - **Title**: `Libur Hari Raya Idul Fitri`
   - **Image**: Upload gambar
     - Recommended ratio: **16:9** (landscape)
     - Max size: 2MB
     - Format: JPG, PNG
   - **Order**: Urutan tampil (1, 2, 3, ...)
   - **Is Active**: Centang ✅ untuk publish
3. Tap **"Create"**

**B. Edit Banner:**
- Tap banner → Edit → Save

**C. Delete Banner:**
- Tap banner → Delete → Confirm

**D. Activate/Deactivate:**
- Toggle switch "Is Active"
- Banner non-aktif tidak muncul di user app

---

## Slide 37: Fitur Admin 8 - User Activity Log

### Monitoring & Audit Trail

**Menu:** (jika ada) → **"Activity Logs"** / **"Audit Trail"**

**Informasi yang Dicatat:**
- User login/logout
- Check-in/check-out activities
- Permit submissions & approvals
- Revision requests & approvals
- Data changes (create/update/delete)
- Admin actions

**Fitur Log:**
- Timestamp lengkap (tanggal & waktu)
- User actor (siapa yang melakukan)
- Action type (login, create, update, delete, dll)
- IP Address
- User Agent (device & browser)

**Use Case:**
- Security audit
- Investigasi fraud/cheating
- Troubleshooting bug
- Compliance & documentation

---

## Slide 38: Tips & Best Practices untuk Admin

### 💡 Tips Admin Optimal

**Setup Awal:**
- ✅ **PERTAMA KALI:** Setup lokasi GPS dengan benar
- ✅ Test check-in/out dari berbagai titik di sekolah
- ✅ Adjust radius jika perlu (terlalu ketat/longgar)
- ✅ Ganti password admin default

**Manajemen User:**
- ✅ Buat user dengan email sekolah (standarisasi)
- ✅ Gunakan format password temporary yang mudah diingat
- ✅ Instruksikan user untuk ganti password setelah login pertama
- ✅ Update data user secara berkala (jika ada perubahan)

**Approval:**
- ✅ Review permit/revision maksimal 1-2 hari kerja
- ✅ Beri catatan yang jelas saat reject
- ✅ Konsisten dalam penerapan aturan
- ✅ Dokumentasikan keputusan penting

**Reporting:**
- ✅ Generate laporan bulanan setiap awal bulan
- ✅ Backup data secara berkala
- ✅ Analisa tren keterlambatan (untuk evaluasi)
- ✅ Share report ke stakeholder (kepala sekolah, dinas)

**Maintenance:**
- ✅ Cek pending approvals setiap hari
- ✅ Monitor dashboard stats untuk anomali
- ✅ Clear old data (opsional, sesuai kebijakan)
- ✅ Update sistem jika ada patch/update baru

---

# SECTION 4: DEMO APLIKASI

---

## Slide 39: Demo Flow - User Journey

### Skenario Demo: Hari Kerja Normal

**Persona:** Ibu Siti, Guru Bahasa Indonesia

### Morning (06:25 AM) - Tiba di Sekolah

**1. Akses Aplikasi:**
- Tap icon "Absensi SMK" di home screen (PWA)
- App loading (± 1 detik)

**2. GPS Aktif:**
- Indikator GPS: 🟢 "GPS Aktif - Lokasi terdeteksi"
- Akurasi: ±8 meter

**3. Home Page:**
- Banner slider muncul (pengumuman event sekolah)
- Card "Status Hari Ini": **Belum Absen**
- Tombol hijau besar: **"Check In"**

**4. Check-In:**
- Tap tombol "Check In"
- Loading animation (± 3 detik)
- ✅ Notifikasi: **"Clock in berhasil! Status: Hadir"**
- Jam masuk tercatat: **06:28:00**
- Status badge: 🟢 **Hadir**

---

## Slide 40: Demo Flow (Lanjutan)

### Afternoon (15:05 PM) - Pulang Sekolah

**5. Check-Out:**
- Buka aplikasi lagi
- Card "Status Hari Ini": Menampilkan jam masuk **06:28:00**
- Tombol biru: **"Check Out"**
- Tap "Check Out"
- Loading (± 3 detik)
- ✅ Notifikasi: **"Clock out berhasil!"**
- Jam pulang tercatat: **15:05:00**

**6. Status Lengkap:**
- Card "Status Hari Ini" update:
  ```
  ✅ Absensi Hari Ini Selesai
  Masuk: 06:28:00
  Pulang: 15:05:00
  Status: Hadir
  ```
- Tombol check-in/out hilang (sudah selesai)

**7. Riwayat:**
- Scroll ke bawah
- Riwayat 5 hari terakhir update
- Hari ini muncul di top list

---

## Slide 41: Demo Flow - Pengajuan Izin

### Skenario: Sakit Besok (Malam Hari)

**Persona:** Ibu Siti merasa kurang sehat, prediksi tidak bisa masuk besok

### Evening (19:00 PM) - Di Rumah

**1. Buka Aplikasi:**
- Tap icon app
- Tap menu **"Permits"** (bottom nav)

**2. Buat Pengajuan:**
- Tap **"Ajukan Izin"** (tombol biru)
- Form muncul

**3. Isi Form:**
- **Tanggal**: Pilih besok (12 Des 2025)
- **Jenis**: Pilih **"Sakit"**
- **Alasan**: Ketik `"Demam tinggi, tidak bisa mengajar. Sudah ke dokter."`
- **Lampiran**: Upload foto surat dokter (tap icon upload, pilih dari gallery)
  - File: `surat_dokter.jpg` (500KB)
  - Preview muncul

**4. Submit:**
- Tap **"Submit"**
- Loading
- ✅ Notifikasi: **"Pengajuan izin berhasil dikirim. Menunggu approval admin."**
- Redirect ke list permits
- Card baru muncul:
  ```
  📅 12 Des 2025
  🏥 Sakit
  ⏳ Status: Pending
  Alasan: Demam tinggi, tidak bisa mengajar...
  ```

---

## Slide 42: Demo Flow - Admin Approval

### Skenario: Admin Me-review Izin

**Persona:** Pak Admin, di kantor sekolah

### Next Day Morning (07:00 AM)

**1. Login Admin:**
- Buka browser: `https://school.app/admin`
- Login dengan kredensial admin

**2. Dashboard Notification:**
- Widget "Pending Approvals": **1 Izin menunggu**
- Tap widget / klik menu **"Permits"**

**3. Filter Pending:**
- Otomatis filter status **"Pending"**
- Muncul 1 card:
  ```
  Ibu Siti Nurhaliza
  Tanggal: 12 Des 2025
  Jenis: Sakit
  Status: Pending
  Submitted: 11 Des 2025, 19:05
  ```

**4. Review Detail:**
- Tap card
- Lihat detail lengkap:
  - Alasan: "Demam tinggi..."
  - Lampiran: Ada (klik untuk preview)
  - Preview surat dokter: ✅ Valid, ada cap & tanda tangan dokter

---

## Slide 43: Demo Flow - Approval (Lanjutan)

**5. Approve:**
- Scroll ke section **"Approval"**
- Status: Pilih dropdown → **"Approved"**
- Admin Notes: Ketik `"Disetujui. Semoga lekas sembuh, Bu Siti."`
- Tap **"Save"**
- Loading
- ✅ Notifikasi: **"Permit approved successfully"**

**6. Sistem Background Process:**
- Status permit update: **Approved** ✅
- **Auto-create attendance:**
  ```
  User: Ibu Siti Nurhaliza
  Date: 12 Des 2025
  Status: Sakit
  Clock In: --
  Clock Out: --
  Note: "Auto-generated from permit approval"
  ```
- Notifikasi ke Ibu Siti (jika ada push notification system)

**7. User Perspective (Ibu Siti):**
- Buka app lagi
- Tap menu **"Permits"**
- Card update:
  ```
  📅 12 Des 2025
  🏥 Sakit
  ✅ Status: Approved
  Catatan Admin: "Disetujui. Semoga lekas sembuh, Bu Siti."
  ```
- Tap menu **"Home"** → Riwayat absensi:
  ```
  12 Des 2025
  Status: 🟠 Sakit
  Keterangan: Izin sakit disetujui
  ```

---

## Slide 44: Demo Flow - Lupa Check-Out

### Skenario: Revisi Absensi

**Persona:** Pak Budi, Guru Matematika

### Yesterday - Lupa Check-Out

**Situasi:**
- Pak Budi check-in pagi: **06:30:00**
- Sore ada rapat mendadak
- Lupa check-out
- Pulang langsung

### Today Morning (07:00 AM) - Sadar Lupa

**1. Cek Riwayat:**
- Buka app
- Lihat riwayat kemarin:
  ```
  11 Des 2025
  Masuk: 06:30:00
  Pulang: -- (belum check-out)
  Status: ⚠️ Incomplete
  ```

**2. Ajukan Revisi:**
- Tap menu **"Revisions"**
- Tap **"Ajukan Revisi"**

**3. Isi Form:**
- **Pilih Absensi**: Dropdown → Pilih `11 Des 2025 - Incomplete`
- **Waktu Check-out Baru**: Time picker → Pilih `15:30:00`
- **Alasan**: Ketik:
  ```
  "Lupa check-out karena rapat mendadak dengan kepala sekolah 
  membahas persiapan ujian semester. Rapat selesai pukul 15:30 
  dan langsung pulang."
  ```
- **Bukti** (opsional): Upload foto undangan rapat / screenshot email

---

## Slide 45: Demo Flow - Revisi (Lanjutan)

**4. Submit:**
- Tap **"Submit"**
- ✅ Notifikasi: **"Pengajuan revisi berhasil dikirim"**
- Card muncul di list:
  ```
  📅 11 Des 2025
  Revisi: Check-out ke 15:30:00
  ⏳ Status: Pending
  ```

### Admin Side - Review Revisi

**1. Admin Login:**
- Menu **"Attendance Revisions"**
- Muncul 1 pending revision

**2. Review:**
- User: Pak Budi
- Data Lama: Check-out: `--`
- Data Baru: Check-out: `15:30:00`
- Alasan: "Lupa check-out karena rapat..."
- Bukti: Ada (foto undangan rapat) ✅

**3. Approve:**
- Status: **"Approved"**
- Tap **"Save"**
- 🎉 Data absensi otomatis ter-update:
  ```
  11 Des 2025
  Masuk: 06:30:00
  Pulang: 15:30:00 (Revised)
  Status: ✅ Hadir
  ```

**4. User Perspective (Pak Budi):**
- Cek riwayat:
  ```
  11 Des 2025
  Masuk: 06:30:00
  Pulang: 15:30:00
  Status: ✅ Hadir
  Badge: 🔄 Revisi
  ```

---

## Slide 46: Demo Flow - Generate Laporan

### Skenario: Admin Buat Laporan Bulanan

**Persona:** Pak Admin, akhir bulan

### End of Month (30 Des 2025)

**1. Akses Reports:**
- Menu **"Reports"** → **"Monthly Attendance Report"**

**2. Pilih Periode:**
- **Bulan**: Desember
- **Tahun**: 2025
- Tap **"Generate Report"**

**3. Loading:**
- Loading spinner (± 5 detik untuk 50 users)

**4. Hasil Report:**
- Tabel muncul:

| User | Total Hari | Hadir | Telat | Izin | Sakit | Alpha | Attendance Rate |
|------|------------|-------|-------|------|-------|-------|-----------------|
| Ibu Siti | 20 | 17 | 1 | 1 | 1 | 0 | 95% |
| Pak Budi | 20 | 18 | 2 | 0 | 0 | 0 | 100% |
| ... | ... | ... | ... | ... | ... | ... | ... |

**5. Export:**
- Tap **"Export Excel"**
- Download file: `Monthly_Report_Desember_2025.xlsx`

**6. Share:**
- Email file ke kepala sekolah
- Print untuk arsip
- Upload ke sistem dinas pendidikan

---

## Slide 47: Demo Flow - GPS Error Handling

### Skenario: Troubleshooting GPS

**Persona:** Ibu Ani, user baru

### Problem: GPS Error

**1. Situasi:**
- Ibu Ani buka app
- Indikator GPS: 🔴 **"GPS Error - Klik untuk info"**
- Tombol "Check In" disabled (abu-abu)

**2. Troubleshoot:**
- Tap indikator GPS merah
- Popup muncul dengan troubleshooting steps:
  ```
  GPS Error - Langkah Perbaikan:
  
  1. ✅ Pastikan Location Services aktif:
     Settings → Privacy → Location → On
  
  2. ✅ Izinkan akses lokasi untuk browser:
     - Chrome: Tap 🔒 → Site settings → Location → Allow
  
  3. ✅ Coba di area terbuka (bukan basement)
  
  4. ✅ Refresh halaman
  
  5. Jika masih error, hubungi admin.
  ```

**3. Fix:**
- Ibu Ani cek Settings → Location → Off ❌
- Turn ON ✅
- Refresh browser
- Indikator GPS: 🟢 **"GPS Aktif - Lokasi terdeteksi"**
- Tombol "Check In" aktif (hijau)

---

## Slide 48: Demo Flow - Di Luar Radius

### Skenario: Coba Check-In dari Luar Area

**Persona:** Pak Dedi (naughty user 😅)

### Attempt: Check-In dari Warung Seberang

**1. Situasi:**
- Pak Dedi di warung kopi seberang sekolah (jarak ± 150 meter)
- GPS aktif: 🟢

**2. Coba Check-In:**
- Tap tombol **"Check In"**
- Loading (± 3 detik)
- ❌ Error muncul:
  ```
  ⚠️ Gagal Check-In
  
  Anda berada di luar area absensi.
  Jarak Anda dari sekolah: 147 meter
  Jarak maksimal diizinkan: 100 meter
  
  Silakan mendekati lokasi sekolah dan coba lagi.
  ```

**3. Pak Dedi's Action:**
- Jalan ke dalam area sekolah
- GPS update lokasi
- Coba check-in lagi
- ✅ Berhasil (jarak sekarang: 45 meter)

**Admin Perspective:**
- Admin bisa lihat attempt log (jika ada)
- Koordinat tersimpan untuk audit

---

## Slide 49: Video Demo (Placeholder)

### 🎥 Live Demo Video

**Konten Video (5-7 menit):**

**Part 1: User App (2-3 menit)**
- 0:00 - Login & izin GPS
- 0:30 - Tour interface (home, permits, revisions, profile)
- 1:00 - Demo check-in (sukses)
- 1:30 - Demo check-out (sukses)
- 2:00 - Ajukan izin/sakit
- 2:30 - Lihat riwayat absensi

**Part 2: Admin Dashboard (3-4 menit)**
- 0:00 - Login admin panel
- 0:30 - Tour dashboard (widgets, menu)
- 1:00 - Setup lokasi GPS
- 1:30 - CRUD user baru
- 2:00 - Monitoring absensi (lihat data + lokasi)
- 2:30 - Approve permit
- 3:00 - Approve revision
- 3:30 - Generate & export laporan

**Catatan:**
- Gunakan screen recorder (OBS / QuickTime)
- Edit dengan subtitle/caption
- Background music ringan
- Voiceover menjelaskan setiap step

---

## Slide 50: Screenshot Gallery

### 📸 Galeri Screenshot Aplikasi

**User App Screenshots:**

**1. Home Page**
- Banner slider
- Status GPS
- Tombol Check-In
- Riwayat 5 hari

**2. Permits Page**
- List permits dengan status badge
- Form pengajuan izin

**3. Revisions Page**
- List revisions
- Form pengajuan revisi

**4. Profile Page**
- Info personal
- Ganti password
- Riwayat lengkap

---

## Slide 51: Screenshot Gallery (Admin)

### 📸 Admin Dashboard Screenshots

**Admin Screenshots:**

**1. Dashboard**
- Widgets statistik
- Chart kehadiran
- Pending approvals
- Recent activities

**2. Location Settings**
- Form input koordinat
- Google Maps preview
- Radius circle

**3. Users Management**
- Table users
- Create/Edit form

**4. Attendances**
- Table absensi
- Detail dengan lokasi GPS
- Filter options

**5. Approval Pages**
- Permits approval
- Revisions approval
- With admin notes

**6. Reports**
- Monthly report table
- Export button

---

# SECTION 5: PENUTUP & Q&A

---

## Slide 52: Summary & Key Takeaways

### 📝 Kesimpulan

**Apa yang Sudah Kita Pelajari:**

**1. Pengenalan Aplikasi**
- ✅ Sistem absensi berbasis GPS
- ✅ PWA untuk akses mobile
- ✅ Teknologi modern (Laravel, Filament, Livewire)

**2. Fitur User**
- ✅ Check-In/Out dengan validasi GPS
- ✅ Pengajuan izin/sakit dengan lampiran
- ✅ Revisi absensi untuk koreksi data
- ✅ Riwayat absensi lengkap

**3. Fitur Admin**
- ✅ Setup lokasi GPS & radius
- ✅ Manajemen user (CRUD)
- ✅ Approval system untuk permit & revision
- ✅ Reporting & export Excel
- ✅ Monitoring lokasi absensi

**4. Demo Real-World**
- ✅ Complete user journey
- ✅ Admin workflow
- ✅ Error handling & troubleshooting

---

## Slide 53: Benefits Recap

### 🌟 Manfaat Implementasi Sistem

**Untuk Guru & Staff:**
- 📱 Absensi cepat & mudah via HP
- 🏠 Tidak perlu ke ruang TU untuk absen manual
- 📊 Transparansi data kehadiran
- 🕐 Hemat waktu (< 10 detik per absensi)

**Untuk Admin/TU:**
- 🤖 Otomasi approval & laporan
- 📍 Validasi lokasi otomatis (no fraud)
- 📈 Dashboard real-time monitoring
- 💾 Arsip data digital (paperless)

**Untuk Sekolah:**
- 💰 Cost-effective (no hardware mahal)
- 🎯 Akurasi data 100%
- 📊 Data untuk evaluasi kinerja
- 🌱 Eco-friendly (no kertas)

---

## Slide 54: Roadmap & Future Features

### 🚀 Pengembangan Ke Depan

**Planned Features (Future Updates):**

**1. Push Notifications**
- Notifikasi real-time untuk approval
- Reminder check-in/out

**2. Face Recognition**
- Verifikasi wajah saat absen
- Anti-fraud tambahan

**3. QR Code Check-In**
- Alternatif GPS untuk indoor
- Scan QR di setiap ruangan

**4. Mobile App Native**
- Android & iOS native app
- Better performance & UX

**5. Integration**
- Integrasi dengan sistem payroll
- Integrasi dengan e-learning
- API untuk eksternal system

**6. Advanced Analytics**
- ML-powered insights
- Prediksi keterlambatan
- Anomaly detection

---

## Slide 55: Getting Started Checklist

### ✅ Checklist Implementasi

**For Admin - First Time Setup:**

- [ ] **1. Install & Deploy Aplikasi**
  - Server setup
  - Database setup
  - SSL certificate (HTTPS)

- [ ] **2. Login Admin Panel**
  - Change default password
  - Update admin profile

- [ ] **3. Setup Lokasi GPS** ⭐ (CRITICAL)
  - Get school coordinates from Google Maps
  - Set latitude & longitude
  - Adjust radius (recommended: 100m)
  - Test from multiple points

- [ ] **4. Tambah Data User**
  - Input semua guru & staff
  - Set role, department, jam kerja
  - Share credentials ke masing-masing user

- [ ] **5. Brief & Training**
  - User training (cara pakai app)
  - Explain GPS requirement
  - Explain workflow & rules

- [ ] **6. Soft Launch (1-2 minggu)**
  - Pilot test dengan beberapa user
  - Collect feedback
  - Fix bugs/issues
  - Adjust radius if needed

- [ ] **7. Full Launch**
  - Mandatory untuk semua user
  - Monitor daily
  - Handle support requests

---

## Slide 56: Support & Resources

### 📚 Dokumentasi & Dukungan

**Dokumentasi Lengkap:**
- 📖 **README.md** - Overview sistem
- 🔧 **API_REFERENCE.md** - Technical API docs
- 📍 **LOCATION_SETUP_GUIDE.md** - Setup GPS guide
- 🚀 **QUICK_START.md** - Quick start untuk developer
- 📋 **REQUIREMENT.md** - System requirements
- ☁️ **RAILWAY_DEPLOYMENT.md** - Deploy ke Railway

**Support Channel:**
- 💬 WhatsApp: +62-XXX-XXXX-XXXX
- 📧 Email: support@school.sch.id
- 🌐 Website: https://your-school.sch.id/help
- 🎫 Ticketing System: (jika ada)

**Training Materials:**
- 🎥 Video tutorial (YouTube playlist)
- 📄 PDF user guide (downloadable)
- 🖼️ Infographic poster (untuk ditempel di sekolah)

---

## Slide 57: FAQ (Frequently Asked Questions)

### ❓ Pertanyaan yang Sering Ditanyakan

**Q1: Apakah wajib pakai smartphone?**
A: Ya, karena GPS hanya tersedia di smartphone/tablet. Browser desktop tidak support GPS secara akurat.

**Q2: Bagaimana jika HP kehabisan baterai?**
A: Ajukan revisi absensi keesokan harinya dengan alasan "HP mati". Sertakan bukti jika memungkinkan.

**Q3: Apakah bisa absen tanpa internet?**
A: Tidak, aplikasi memerlukan internet untuk sync data ke server.

**Q4: Bagaimana jika GPS error terus?**
A: 
1. Cek Settings → Location Services → On
2. Izinkan akses lokasi untuk browser
3. Coba di area terbuka (bukan basement)
4. Hubungi admin jika masih error

**Q5: Berapa toleransi keterlambatan?**
A: Default 5 menit setelah jam kerja. Contoh: Jam kerja 06:30, toleransi sampai 06:35.

**Q6: Apakah koordinat GPS saya disimpan?**
A: Ya, untuk keperluan audit & validasi. Hanya admin yang bisa akses data ini.

**Q7: Bagaimana jika lupa password?**
A: Hubungi admin untuk reset password.

**Q8: Apakah bisa absen untuk orang lain?**
A: Tidak, dan akan terdeteksi (koordinat GPS berbeda). Ini termasuk pelanggaran.

---

## Slide 58: Best Practices

### 💡 Tips & Best Practices

**For Users:**
- 🔋 Charge HP sebelum berangkat sekolah
- 📱 Install sebagai PWA untuk akses cepat
- 🌍 Aktifkan GPS sebelum buka app
- ⏰ Check-in segera saat tiba (jangan tunda)
- 📝 Check-out sebelum pulang (jangan lupa!)
- 🔐 Ganti password default setelah login pertama

**For Admin:**
- 📍 Setup lokasi GPS dengan akurat di awal
- 🧪 Test dari berbagai titik di area sekolah
- 📊 Review pending approvals setiap hari
- 📈 Generate laporan bulanan tepat waktu
- 💾 Backup database secara berkala
- 📧 Respond to support requests cepat

**For School Management:**
- 📋 Buat SOP penggunaan sistem yang jelas
- 🎓 Training berkala untuk user baru
- 📊 Evaluasi data kehadiran secara periodik
- 🏆 Reward untuk attendance rate tinggi
- ⚖️ Enforcement konsisten untuk pelanggaran

---

## Slide 59: Contact & Credits

### 📞 Kontak

**Development Team:**
- 🏫 Sekolah: SMK Negeri 1 Jakarta
- 👨‍💻 Developer: [Your Name/Team Name]
- 📧 Email: dev@school.sch.id
- 🌐 Website: https://your-school.sch.id

**Technical Support:**
- 💬 WhatsApp: +62-XXX-XXXX-XXXX (Admin TI)
- 📧 Email: it.support@school.sch.id
- ⏰ Jam Operasional: Senin-Jumat, 08:00 - 16:00

**Credits:**
- Built with ❤️ using Laravel, Filament, Livewire
- Icon by Heroicons
- Maps by Google Maps API
- Hosting by Railway / Your Hosting Provider

---

## Slide 60: Thank You & Q&A

### 🙏 Terima Kasih!

**Sistem Absensi SMK**
*Modern, Akurat, Transparan*

---

**📱 Ready to Go Digital?**

Mari bersama-sama meningkatkan efisiensi dan transparansi absensi di sekolah kita!

---

**Questions & Answers**

*Silakan ajukan pertanyaan Anda* 🙋‍♂️🙋‍♀️

---

**Contact Us:**
📧 info@school.sch.id
📱 +62-XXX-XXXX-XXXX
🌐 https://your-school.sch.id

---

© 2025 PKM Absensi System | All Rights Reserved
