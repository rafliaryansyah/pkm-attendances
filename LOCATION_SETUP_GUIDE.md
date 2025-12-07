# Panduan Setup Lokasi Absensi

## Fitur yang Telah Dibuat

### 1. Settings Table
Database untuk menyimpan konfigurasi lokasi absensi dengan field:
- `attendance_location_lat` - Latitude lokasi
- `attendance_location_long` - Longitude lokasi  
- `attendance_radius` - Radius dalam meter (default: 100m)
- `attendance_location_name` - Nama lokasi

### 2. Model Setting (`app/Models/Setting.php`)
Helper methods:
- `Setting::get($key, $default)` - Ambil nilai setting
- `Setting::set($key, $value)` - Set nilai setting
- `Setting::getAttendanceLocation()` - Ambil konfigurasi lokasi
- `Setting::isAttendanceLocationSet()` - Cek apakah lokasi sudah di-set

### 3. LocationHelper (`app/Helpers/LocationHelper.php`)
Utility untuk kalkulasi jarak dan validasi:
- `calculateDistance()` - Hitung jarak antara 2 koordinat (Haversine formula)
- `isWithinRadius()` - Cek apakah dalam radius
- `validateAttendanceLocation()` - Validasi lokasi user untuk absensi

### 4. Filament Admin Page
Halaman admin di `/admin` → **Pengaturan > Pengaturan Lokasi**
Fitur:
- Form input lokasi (lat, long, radius, nama)
- Preview map Google Maps
- Status lokasi aktif/tidak aktif
- Reset lokasi

### 5. API Endpoints
Routes di `routes/api.php`:

#### GET `/api/attendance/location/config`
Mengambil konfigurasi lokasi absensi
```json
{
  "success": true,
  "message": "Lokasi absensi ditemukan",
  "data": {
    "lat": -6.200000,
    "long": 106.816666,
    "radius": 100,
    "name": "Kantor Pusat"
  }
}
```

Jika belum di-set:
```json
{
  "success": false,
  "message": "Lokasi absensi belum ditentukan oleh admin. Silakan hubungi administrator.",
  "data": null
}
```

#### POST `/api/attendance/location/check`
Cek apakah lokasi user valid untuk absensi
```json
Request:
{
  "latitude": -6.200123,
  "longitude": 106.816789
}

Response:
{
  "success": true,
  "message": "Lokasi valid untuk absensi",
  "data": {
    "distance": 45.32,
    "max_distance": 100,
    "location_name": "Kantor Pusat"
  }
}
```

Jika di luar radius:
```json
{
  "success": false,
  "message": "Anda berada 250.45 meter dari lokasi absensi. Jarak maksimal: 100 meter",
  "data": {
    "distance": 250.45,
    "max_distance": 100
  }
}
```

#### POST `/api/attendance/clock-in`
Clock in dengan validasi lokasi
```json
Request:
{
  "latitude": -6.200123,
  "longitude": 106.816789
}

Response:
{
  "success": true,
  "message": "Clock in berhasil",
  "data": {
    "attendance": {...},
    "is_late": false
  }
}
```

#### POST `/api/attendance/clock-out`
Clock out dengan validasi lokasi
```json
Request:
{
  "latitude": -6.200123,
  "longitude": 106.816789
}

Response:
{
  "success": true,
  "message": "Clock out berhasil",
  "data": {
    "attendance": {...}
  }
}
```

## Instalasi

1. **Jalankan Migration**
```bash
php artisan migrate
```

2. **Install Package Excel (untuk laporan)**
```bash
composer require maatwebsite/excel
```

3. **Clear Cache**
```bash
php artisan cache:clear
php artisan config:clear
```

## Cara Penggunaan

### Untuk Admin:
1. Login ke `/admin`
2. Buka menu **Pengaturan > Pengaturan Lokasi**
3. Dapatkan koordinat dari Google Maps:
   - Buka https://www.google.com/maps
   - Cari lokasi sekolah/kantor
   - Klik kanan pada titik lokasi
   - Copy koordinat (contoh: -6.200000, 106.816666)
4. Input koordinat ke form (pisahkan lat dan long)
5. Atur radius (disarankan 50-200 meter)
6. Klik **Simpan Pengaturan**

### Untuk Developer Frontend:
1. **Ambil Konfigurasi Lokasi**
```javascript
const response = await fetch('/api/attendance/location/config', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Accept': 'application/json'
  }
});

const data = await response.json();

if (!data.success) {
  // Tampilkan pesan: "Lokasi absensi belum ditentukan admin"
  alert(data.message);
  return;
}

// Gunakan data.data.lat, data.data.long, data.data.radius
```

2. **Ambil Lokasi User (Browser Geolocation API)**
```javascript
navigator.geolocation.getCurrentPosition(
  (position) => {
    const userLat = position.coords.latitude;
    const userLong = position.coords.longitude;
    
    // Validasi lokasi
    checkLocation(userLat, userLong);
  },
  (error) => {
    alert('Gagal mendapatkan lokasi. Pastikan GPS aktif.');
  }
);
```

3. **Validasi Lokasi Sebelum Absen**
```javascript
async function checkLocation(lat, long) {
  const response = await fetch('/api/attendance/location/check', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + token,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      latitude: lat,
      longitude: long
    })
  });
  
  const data = await response.json();
  
  if (!data.success) {
    alert(data.message);
    return false;
  }
  
  // Lanjut ke proses clock in/out
  return true;
}
```

4. **Clock In**
```javascript
async function clockIn(lat, long) {
  // Validasi dulu
  const isValid = await checkLocation(lat, long);
  if (!isValid) return;
  
  const response = await fetch('/api/attendance/clock-in', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + token,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      latitude: lat,
      longitude: long
    })
  });
  
  const data = await response.json();
  
  if (data.success) {
    alert('Clock in berhasil!');
    if (data.data.is_late) {
      alert('Anda terlambat!');
    }
  } else {
    alert(data.message);
  }
}
```

## Error Messages

1. **Lokasi belum di-set admin:**
   "Lokasi absensi belum ditentukan oleh admin. Silakan hubungi administrator."

2. **User di luar radius:**
   "Anda berada X meter dari lokasi absensi. Jarak maksimal: Y meter"

3. **GPS tidak aktif:**
   "Gagal mendapatkan lokasi. Pastikan GPS aktif."

4. **Sudah clock in:**
   "Anda sudah melakukan clock in hari ini"

## Tips
- Radius 50-100m cocok untuk gedung kecil
- Radius 100-200m cocok untuk kompleks/kampus
- Gunakan WiFi + GPS untuk akurasi terbaik
- Test lokasi dulu sebelum production

## Troubleshooting

1. **Admin tidak bisa akses halaman settings:**
   - Pastikan user memiliki role 'admin'
   - Clear cache: `php artisan cache:clear`

2. **API return 401 Unauthorized:**
   - Pastikan menggunakan Laravel Sanctum token
   - Cek middleware auth:sanctum di routes

3. **Jarak tidak akurat:**
   - Pastikan GPS user aktif
   - Tunggu beberapa detik untuk GPS accuracy tinggi
   - Cek koordinat admin sudah benar

4. **Error "Class LocationHelper not found":**
   - Run: `composer dump-autoload`
