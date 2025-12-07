<?php

use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('attendance')->group(function () {
        Route::get('location/config', [AttendanceController::class, 'getLocationConfig']);
        Route::post('location/check', [AttendanceController::class, 'checkLocation']);
        Route::post('clock-in', [AttendanceController::class, 'clockIn']);
        Route::post('clock-out', [AttendanceController::class, 'clockOut']);
    });
});
