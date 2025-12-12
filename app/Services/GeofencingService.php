<?php

namespace App\Services;

class GeofencingService
{
    // Target location - SMK Coordinates
    private const TARGET_LATITUDE = -6.154902;
    private const TARGET_LONGITUDE = 106.772247;
    private const ALLOWED_RADIUS = 200; // in meters

    /**
     * Calculate distance between two coordinates using Haversine Formula
     *
     * @param float $lat1 Latitude of point 1
     * @param float $lon1 Longitude of point 1
     * @param float $lat2 Latitude of point 2
     * @param float $lon2 Longitude of point 2
     * @return float Distance in meters
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // Earth radius in meters

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Check if coordinates are within allowed geofence
     *
     * @param float $latitude User's latitude
     * @param float $longitude User's longitude
     * @return bool
     */
    public function isWithinGeofence(float $latitude, float $longitude): bool
    {
        $distance = $this->calculateDistance(
            $latitude,
            $longitude,
            self::TARGET_LATITUDE,
            self::TARGET_LONGITUDE
        );

        return $distance <= self::ALLOWED_RADIUS;
    }

    /**
     * Get distance from target location
     *
     * @param float $latitude User's latitude
     * @param float $longitude User's longitude
     * @return float Distance in meters
     */
    public function getDistanceFromTarget(float $latitude, float $longitude): float
    {
        return $this->calculateDistance(
            $latitude,
            $longitude,
            self::TARGET_LATITUDE,
            self::TARGET_LONGITUDE
        );
    }

    /**
     * Get target location coordinates
     *
     * @return array
     */
    public function getTargetLocation(): array
    {
        return [
            'latitude' => self::TARGET_LATITUDE,
            'longitude' => self::TARGET_LONGITUDE,
            'radius' => self::ALLOWED_RADIUS,
        ];
    }
}
