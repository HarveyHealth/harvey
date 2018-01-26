<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Lib\Geopoint;

class AvailableLab extends Model
{
    const MINIMUM_DISTANCE = 10000; // in miles

    static function closestLabToPoint(Geopoint $center_point)
    {
        // this will only work for US lat/lngs. You
        // could modify it to work with global lat/lngs,
        // but it was unnecessary here

        $latitude = $center_point->latitude;
        $longitude = $center_point->longitude;

        $top_lat = $latitude - 1;
        $bottom_lat = $latitude + 1;

        $left_lng = $longitude - 1;
        $right_lng = $longitude + 1;

        \DB::enableQueryLog();

        $labs = AvailableLab::whereBetween('longitude', [$left_lng, $right_lng])
                    ->whereBetween('latitude', [$top_lat, $bottom_lat])
                    ->get();

        $min_distance = self::MINIMUM_DISTANCE; // set something high
        $closest_lab = null;

        foreach ($labs as $lab) {
            $distance = $center_point->distanceToPoint(new Geopoint($lab->latitude, $lab->longitude), 'mi');

            if ($distance < $min_distance) {
                $closest_lab = $lab;
                $min_distance = $distance;
            }
        }

        if ($min_distance >= self::MINIMUM_DISTANCE)
            return false;

        return [
            'distance' => $min_distance,
            'available_lab_id' => $closest_lab->id
        ];
    }
}
