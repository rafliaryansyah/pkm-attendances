<?php

namespace App\Helpers;

class LocationHelper
{
    public static function calculateDistance(
        float $lat1,
        float $long1,
        float $lat2,
        float $long2
    ): float {
        $earthRadius = 6371000;

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($long1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($long2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public static function isWithinRadius(
        float $userLat,
        float $userLong,
        float $targetLat,
        float $targetLong,
        float $radius
    ): bool {
        $distance = self::calculateDistance($userLat, $userLong, $targetLat, $targetLong);
        return $distance <= $radius;
    }

    public static function validateAttendanceLocation(float $userLat, float $userLong): array
    {
        $location = \App\Models\Setting::getAttendanceLocation();

        if ($location === null) {
            return [
                'valid' => false,
                'message' => 'Lokasi absensi belum ditentukan oleh admin. Silakan hubungi administrator.',
                'distance' => null,
            ];
        }

        $distance = self::calculateDistance(
            $userLat,
            $userLong,
            $location['lat'],
            $location['long']
        );

        $isValid = $distance <= $location['radius'];

        return [
            'valid' => $isValid,
            'message' => $isValid
                ? 'Lokasi valid untuk absensi'
                : sprintf(
                    'Anda berada %.2f meter dari lokasi absensi. Jarak maksimal: %d meter',
                    $distance,
                    $location['radius']
                ),
            'distance' => round($distance, 2),
            'max_distance' => $location['radius'],
            'location_name' => $location['name'],
        ];
    }
}
