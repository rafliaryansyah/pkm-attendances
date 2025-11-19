<?php

namespace App\Services;

class GeolocationService
{
    // School location coordinates
    const SCHOOL_LAT = -6.154928;
    const SCHOOL_LONG = 106.772240;
    const ALLOWED_RADIUS = 80; // meters

    /**
     * Calculate distance between two coordinates using Haversine formula
     *
     * @param float $lat1 Latitude of first point
     * @param float $lon1 Longitude of first point
     * @param float $lat2 Latitude of second point
     * @param float $lon2 Longitude of second point
     * @return float Distance in meters
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Earth radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Check if coordinates are within allowed radius from school
     *
     * @param float $lat User's latitude
     * @param float $long User's longitude
     * @return bool
     */
    public static function isWithinAllowedRadius(float $lat, float $long): bool
    {
        $distance = self::calculateDistance(
            self::SCHOOL_LAT,
            self::SCHOOL_LONG,
            $lat,
            $long
        );

        return $distance <= self::ALLOWED_RADIUS;
    }

    /**
     * Get distance from school location
     *
     * @param float $lat User's latitude
     * @param float $long User's longitude
     * @return float Distance in meters
     */
    public static function getDistanceFromSchool(float $lat, float $long): float
    {
        return self::calculateDistance(
            self::SCHOOL_LAT,
            self::SCHOOL_LONG,
            $lat,
            $long
        );
    }
}
