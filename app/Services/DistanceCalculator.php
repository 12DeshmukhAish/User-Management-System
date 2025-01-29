<?php
// app/Services/DistanceCalculator.php

namespace App\Services;

class DistanceCalculator
{
    private const EARTH_RADIUS_KM = 6371;
    private const EARTH_RADIUS_MILES = 3959;

    /**
     * Calculate distance between two points using Haversine formula
     * 
     * @param float $lat1 Latitude of first point
     * @param float $lng1 Longitude of first point
     * @param float $lat2 Latitude of second point
     * @param float $lng2 Longitude of second point
     * @param bool $inMiles Whether to return distance in miles (default: false, returns km)
     * @return float|null Distance between points in km/miles, or null if invalid coordinates
     */
    public function calculateDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2,
        bool $inMiles = false
    ): ?float {
        // Validate coordinates
        if (!$this->isValidCoordinate($lat1, $lng1) || !$this->isValidCoordinate($lat2, $lng2)) {
            return null;
        }

        // Convert coordinates to radians
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // Haversine Formula
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;

        $a = sin($dlat/2) * sin($dlat/2) +
             cos($lat1) * cos($lat2) *
             sin($dlng/2) * sin($dlng/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        // Calculate distance using appropriate Earth radius
        $radius = $inMiles ? self::EARTH_RADIUS_MILES : self::EARTH_RADIUS_KM;
        $distance = $radius * $c;

        return round($distance, 2);
    }

    /**
     * Validate if coordinates are within valid range
     * 
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @return bool
     */
    private function isValidCoordinate(float $lat, float $lng): bool
    {
        return $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180;
    }
}