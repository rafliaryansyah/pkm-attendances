# 📚 API Reference - Sistem Absensi SMK

## Overview

Sistem ini menggunakan Livewire untuk reactive components, sehingga tidak ada REST API tradisional. Semua komunikasi frontend-backend dilakukan melalui Livewire components.

## Livewire Components

### 1. AttendanceCheckIn Component

**File**: `app/Livewire/AttendanceCheckIn.php`

**Public Properties:**
- `$latitude` - User's current latitude
- `$longitude` - User's current longitude
- `$status` - Status message (success/error)
- `$message` - Message text
- `$todayAttendance` - Today's attendance record
- `$recentAttendances` - Last 5 days attendance
- `$targetLocation` - Target coordinates & radius

**Public Methods:**

#### clockIn()
Check-in user dengan validasi geofencing.

**Requirements:**
- Valid GPS coordinates
- Within 80m radius
- No existing check-in today

**Response:**
```php
[
    'success' => true/false,
    'message' => 'Clock in berhasil!' / 'Error message',
    'attendance' => Attendance Model,
    'status' => 'present' / 'late'
]
```

#### clockOut()
Check-out user dengan validasi geofencing.

**Requirements:**
- Valid GPS coordinates
- Within 80m radius
- Has check-in today
- No existing check-out

**Response:**
```php
[
    'success' => true/false,
    'message' => 'Clock out berhasil!' / 'Error message',
    'attendance' => Attendance Model
]
```

### 2. PermitRequest Component

**File**: `app/Livewire/PermitRequest.php`

**Public Properties:**
- `$type` - 'sick' / 'permit'
- `$start_date` - Start date
- `$end_date` - End date
- `$reason` - Reason text
- `$attachment` - File upload
- `$message` - Status message
- `$status` - Status indicator

**Public Methods:**

#### submit()
Submit permit request.

**Validation:**
```php
[
    'type' => 'required|in:sick,permit',
    'start_date' => 'required|date',
    'end_date' => 'required|date|after_or_equal:start_date',
    'reason' => 'required|string|min:10',
    'attachment' => 'nullable|file|max:2048',
]
```

### 3. RevisionRequest Component

**File**: `app/Livewire/RevisionRequest.php`

**Public Properties:**
- `$date` - Date to revise
- `$proposed_clock_in` - Proposed check-in time
- `$proposed_clock_out` - Proposed check-out time
- `$reason` - Reason for revision
- `$message` - Status message
- `$status` - Status indicator

**Public Methods:**

#### submit()
Submit revision request.

**Validation:**
```php
[
    'date' => 'required|date|before_or_equal:today',
    'proposed_clock_in' => 'required',
    'proposed_clock_out' => 'required',
    'reason' => 'required|string|min:10',
]
```

## Services

### GeofencingService

**File**: `app/Services/GeofencingService.php`

**Constants:**
```php
TARGET_LATITUDE = -6.154928
TARGET_LONGITUDE = 106.772240
ALLOWED_RADIUS = 80 // meters
```

**Methods:**

#### calculateDistance($lat1, $lon1, $lat2, $lon2): float
Calculate distance between two coordinates using Haversine Formula.

**Parameters:**
- `$lat1` - Latitude point 1
- `$lon1` - Longitude point 1
- `$lat2` - Latitude point 2
- `$lon2` - Longitude point 2

**Returns:** Distance in meters

#### isWithinGeofence($latitude, $longitude): bool
Check if coordinates are within allowed radius.

**Returns:** `true` if within radius, `false` otherwise

#### getDistanceFromTarget($latitude, $longitude): float
Get distance from target location.

**Returns:** Distance in meters

#### getTargetLocation(): array
Get target location configuration.

**Returns:**
```php
[
    'latitude' => -6.154928,
    'longitude' => 106.772240,
    'radius' => 80,
]
```

### AttendanceService

**File**: `app/Services/AttendanceService.php`

#### clockIn($user, $latitude, $longitude): array
Process check-in for user.

**Business Logic:**
1. Validate geofence
2. Check existing attendance today
3. Determine status (present/late)
4. Create attendance record

**Returns:**
```php
[
    'success' => bool,
    'message' => string,
    'attendance' => Attendance|null,
    'status' => 'present'|'late'|null
]
```

#### clockOut($user, $latitude, $longitude): array
Process check-out for user.

#### getTodayAttendance($user): ?Attendance
Get today's attendance for user.

#### getRecentAttendances($user, $days = 5): Collection
Get recent attendances for user.

#### getDashboardStats(): array
Get dashboard statistics for today.

**Returns:**
```php
[
    'present' => int,
    'late' => int,
    'permit' => int,
    'sick' => int,
    'alpha' => int,
]
```

### ApprovalService

**File**: `app/Services/ApprovalService.php`

#### approvePermit($permit, $adminNote = null): void
Approve permit and auto-create attendance records.

#### rejectPermit($permit, $adminNote = null): void
Reject permit request.

#### approveRevision($revision): void
Approve revision and update/create attendance record.

#### rejectRevision($revision): void
Reject revision request.

#### getPendingPermitsCount(): int
Get count of pending permits.

#### getPendingRevisionsCount(): int
Get count of pending revisions.

### ReportingService

**File**: `app/Services/ReportingService.php`

#### getAttendanceReport($startDate, $endDate): Collection
Get attendance report for date range.

#### getMonthlyReport($month, $year): Collection
Get monthly report (24th prev month to 23rd current month).

#### getStatistics($startDate, $endDate): array
Get attendance statistics for date range.

**Returns:**
```php
[
    'total' => int,
    'present' => int,
    'late' => int,
    'permit' => int,
    'sick' => int,
    'alpha' => int,
]
```

#### getUserAttendanceSummary($userId, $startDate, $endDate): array
Get user attendance summary.

**Returns:**
```php
[
    'user_id' => int,
    'total_days' => int,
    'total_attendance' => int,
    'present' => int,
    'late' => int,
    'permit' => int,
    'sick' => int,
    'alpha' => int,
    'attendance_rate' => float, // percentage
]
```

#### exportData($startDate, $endDate): array
Export attendance data to array format.

## Database Models

### User Model

**File**: `app/Models/User.php`

**Fillable:**
```php
[
    'name', 'email', 'phone_number', 'password',
    'role', 'work_start_time', 'work_end_time', 'department'
]
```

**Relationships:**
- `attendances()` - HasMany
- `permits()` - HasMany
- `attendanceRevisions()` - HasMany

**Helper Methods:**
- `isAdmin()` - Check if user is admin
- `isTeacher()` - Check if user is teacher
- `isStaff()` - Check if user is staff

### Attendance Model

**File**: `app/Models/Attendance.php`

**Fillable:**
```php
[
    'user_id', 'date', 'clock_in', 'clock_out',
    'lat_in', 'long_in', 'lat_out', 'long_out',
    'status', 'is_revision', 'note'
]
```

**Casts:**
```php
[
    'date' => 'date',
    'clock_in' => 'datetime',
    'clock_out' => 'datetime',
    'lat_in' => 'decimal:8',
    'long_in' => 'decimal:8',
    'lat_out' => 'decimal:8',
    'long_out' => 'decimal:8',
    'is_revision' => 'boolean',
]
```

### Permit Model

**File**: `app/Models/Permit.php`

**Fillable:**
```php
[
    'user_id', 'type', 'start_date', 'end_date',
    'reason', 'attachment', 'status', 'admin_note'
]
```

**Helper Methods:**
- `isPending()` - Check if status is pending
- `isApproved()` - Check if status is approved
- `isRejected()` - Check if status is rejected

### AttendanceRevision Model

**File**: `app/Models/AttendanceRevision.php`

**Fillable:**
```php
[
    'user_id', 'attendance_id', 'date',
    'proposed_clock_in', 'proposed_clock_out',
    'reason', 'status'
]
```

**Helper Methods:**
- `isPending()` - Check if status is pending
- `isApproved()` - Check if status is approved
- `isRejected()` - Check if status is rejected

## Frontend (JavaScript/Livewire)

### Geolocation API Usage

**File**: `resources/views/livewire/attendance-check-in.blade.php`

```javascript
if (navigator.geolocation) {
    navigator.geolocation.watchPosition(
        (position) => {
            @this.set('latitude', position.coords.latitude);
            @this.set('longitude', position.coords.longitude);
        },
        (error) => {
            console.error('Geolocation error:', error);
        },
        {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        }
    );
}
```

### Service Worker

**File**: `public/sw.js`

- Cache static assets
- Offline support
- Auto-update on version change

## Environment Variables

```env
# App
APP_NAME="SMK Attendance System"
APP_TIMEZONE=Asia/Jakarta

# Database
DB_CONNECTION=mysql
DB_DATABASE=absensi
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database

# Cache
CACHE_STORE=database

# Queue
QUEUE_CONNECTION=database
```

## Error Codes

### Geofencing Errors
- Distance > 80m: "Anda berada di luar area absensi. Jarak: X meter."

### Attendance Errors
- Already checked in: "Anda sudah melakukan clock in hari ini."
- Not checked in: "Anda belum melakukan clock in hari ini."
- Already checked out: "Anda sudah melakukan clock out hari ini."

### GPS Errors
- No coordinates: "Tidak dapat mengambil lokasi Anda. Pastikan GPS aktif."

## Testing

### Unit Testing Example

```php
// Test geofencing
$service = new GeofencingService();
$distance = $service->calculateDistance(
    -6.154928, 106.772240, // Target
    -6.154928, 106.772240  // User
);
$this->assertEquals(0, $distance);

// Test within geofence
$isWithin = $service->isWithinGeofence(-6.154928, 106.772240);
$this->assertTrue($isWithin);
```

---

**Note:** This system uses Livewire for reactive UI, so traditional REST API is not needed. All data exchange happens through Livewire wire protocol.
