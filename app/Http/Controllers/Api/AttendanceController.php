<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LocationHelper;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function checkLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validation = LocationHelper::validateAttendanceLocation(
            $request->latitude,
            $request->longitude
        );

        return response()->json([
            'success' => $validation['valid'],
            'message' => $validation['message'],
            'data' => [
                'distance' => $validation['distance'],
                'max_distance' => $validation['max_distance'] ?? null,
                'location_name' => $validation['location_name'] ?? null,
            ],
        ]);
    }

    public function getLocationConfig()
    {
        $location = Setting::getAttendanceLocation();

        if ($location === null) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi absensi belum ditentukan oleh admin. Silakan hubungi administrator.',
                'data' => null,
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lokasi absensi ditemukan',
            'data' => $location,
        ]);
    }

    public function clockIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validation = LocationHelper::validateAttendanceLocation(
            $request->latitude,
            $request->longitude
        );

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message'],
                'data' => [
                    'distance' => $validation['distance'],
                    'max_distance' => $validation['max_distance'] ?? null,
                ],
            ], 400);
        }

        $today = now()->format('Y-m-d');
        $existingAttendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan clock in hari ini',
            ], 400);
        }

        $workStartTime = auth()->user()->work_start_time;
        $currentTime = now();
        $isLate = false;

        if ($workStartTime) {
            $startTime = now()->setTimeFromTimeString($workStartTime);
            $isLate = $currentTime->gt($startTime);
        }

        $attendance = Attendance::create([
            'user_id' => auth()->id(),
            'date' => $today,
            'clock_in' => $currentTime,
            'lat_in' => $request->latitude,
            'long_in' => $request->longitude,
            'status' => $isLate ? 'late' : 'present',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clock in berhasil',
            'data' => [
                'attendance' => $attendance,
                'is_late' => $isLate,
            ],
        ]);
    }

    public function clockOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validation = LocationHelper::validateAttendanceLocation(
            $request->latitude,
            $request->longitude
        );

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message'],
                'data' => [
                    'distance' => $validation['distance'],
                    'max_distance' => $validation['max_distance'] ?? null,
                ],
            ], 400);
        }

        $today = now()->format('Y-m-d');
        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan clock in',
            ], 400);
        }

        if ($attendance->clock_out) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan clock out hari ini',
            ], 400);
        }

        $attendance->update([
            'clock_out' => now(),
            'lat_out' => $request->latitude,
            'long_out' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clock out berhasil',
            'data' => [
                'attendance' => $attendance->fresh(),
            ],
        ]);
    }
}
