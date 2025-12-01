Project Specification: SMK Attendance System (PWA)
1. Project Overview
Build a web-based Attendance System using Laravel 11. The system is a Single Tenant application (one school only). It consists of two main interfaces:

Admin Dashboard (Web Dashboard Pannel): For managing users, approvals, and reports.
User App (Mobile First UI): For Teachers/Staff to check-in/out, view history, and request permits/revisions.

2. Technology Stack
Backend framework: Laravel 11.x
Language: PHP 8.2+
Database: MySQL 8.0
Admin Panel: FilamentPHP v3 (Recommended for speed) or Standard Blade with Bootstrap/Tailwind.
User PWA Frontend: Laravel Blade + Livewire (for reactive components like Geolocation) + TailwindCSS.
PWA Tools: silviolleite/laravel-pwa or equivalent for manifest/service worker generation.
Maps/Geo: HTML5 Geolocation API (Frontend) + Haversine Formula (Backend validation).
3. Database Schema (Key Tables)
A. users
Extends default Laravel users

id, name, email, phone_number (unique), password
role: ENUM('admin', 'teacher', 'staff')
work_start_time: TIME (Default '06:30:00') -> Flexible per user
work_end_time: TIME (Default '15:00:00')
department: String (Nullable, e.g., "Guru TKJ", "Tata Usaha")
B. attendances
Stores daily logs

id
user_id: FK users.id
date: DATE (Indexed for reporting)
clock_in: DATETIME
clock_out: DATETIME (Nullable)
lat_in, long_in: DECIMAL (For audit)
lat_out, long_out: DECIMAL
status: ENUM('present', 'late', 'permit', 'sick', 'alpha')
is_revision: BOOLEAN (Default false) -> Flag if data was manually revised
note: TEXT (Nullable)
C. permits (Izin/Sakit)
id
user_id: FK
type: ENUM('sick', 'permit')
start_date: DATE
end_date: DATE
reason: TEXT
attachment: String (File path for doctor's note, etc.)
status: ENUM('pending', 'approved', 'rejected')
admin_note: TEXT (Nullable)
D. attendance_revisions (Lupa Checkout/Salah Absen)
id
user_id: FK
attendance_id: FK attendances.id (Nullable - if revising existing row)
date: DATE
proposed_clock_in: DATETIME
proposed_clock_out: DATETIME
reason: TEXT
status: ENUM('pending', 'approved', 'rejected')
4. Key Features & Business Logic
A. Geofencing & Check-in Logic
Target Location:
Latitude: -6.154928
Longitude: 106.772240
Radius: 80 meters
Logic:
Frontend (PWA) gets GPS coords using HTML5 Geolocation.
Frontend calculates distance (optional for UX).
Backend MUST recalculate distance using Haversine formula upon submission to prevent spoofing.
If distance > 80m, reject check-in.
Shift Logic:
Compare clock_in time with users.work_start_time.
Toleransi keterlambatan: 5 minutes (Example: 06:35 is Late).
If clock_in > (work_start_time + 5 mins) -> Status late.
B. User PWA Interface
Login: Email or Phone + Password.
Home:
Big Button: "Check In" (if not in) / "Check Out" (if in).
Show "Today's Timeline": List of other users on Leave/Sick today (Query permits where status='approved' & date=today).
Recent History: Last 5 days attendance list.
Menu: Home, History, Pengajuan (Izin/Sakit/Revisi), Profile.
C. "Lupa Checkout" / Revision Flow
User realizes they forgot to checkout yesterday.
User goes to "Pengajuan Revisi".
User selects Date, inputs correct In/Out time, and Reason.
Admin receives notification (or sees in Dashboard).
If Admin Approves:
System updates/creates the record in attendances table.
Sets is_revision = true.
Marks attendance_revisions status as 'approved'.
D. Admin Dashboard (Filament recommended)
Dashboard: Stats (Hadir, Telat, Izin, Alpha today).
Manage Users: Create/Edit/Delete Teachers & Staff. Set their specific work_start_time.
Approvals:
Tab for Permit/Sick approvals.
Tab for Revision approvals.
Reports (Laporan):
Filter by Date Range.
Special Monthly Filter: From date 24th (prev month) to 23rd (current month).
Export to Excel/PDF.
5. Implementation Plan (Step-by-Step)
Setup: Laravel Install, Database Config, PWA Asset setup.
Models & Migrations: Create tables defined above.
Admin Panel: Setup Filament Resources for Users.
Attendance Logic: Implement Backend Service for calculating Distance and determining 'Late' status.
PWA Frontend: Build Mobile-first views for Login and Check-in using Livewire (to handle GPS async comfortably).
Permit & Revision: Build Forms and Admin Approval logic.
Reporting: Build the Query logic for monthly recap.

Datase Yang Sudah Ada Sebelumya
DATABASE_HOST=localhost
DATABASE_PORT=3306
DATABASE_DATABASE=absensi
DATABASE_USERNAME=root
DATABASE_PASSWORD=