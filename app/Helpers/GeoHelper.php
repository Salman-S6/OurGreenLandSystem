<?php

namespace App\Helpers;

class GeoHelper
{
    /**
     * Calculate approximate area (in hectares) from lat/lng boundary coordinates using Shoelace formula.
     *
     * @param array $coordinates Array of points with 'lat' and 'lng' keys
     * @return float Area in hectares
     */
    public static function calculatePolygonArea(array $coordinates): float
    {
        $earthRadius = 6378137; // Earth's radius in meters

        $rad = fn($deg) => $deg * pi() / 180;

        $area = 0;
        $count = count($coordinates);

        if ($count < 3) return 0;

        for ($i = 0; $i < $count; $i++) {
            $lat1 = $rad($coordinates[$i]['lat']);
            $lng1 = $rad($coordinates[$i]['lng']);

            $j = ($i + 1) % $count;

            $lat2 = $rad($coordinates[$j]['lat']);
            $lng2 = $rad($coordinates[$j]['lng']);

            $area += ($lng2 - $lng1) * (2 + sin($lat1) + sin($lat2));
        }

        $area = abs($area * $earthRadius * $earthRadius / 2.0);

        return round($area / 10000, 2); // convert to hectares
    }
}
