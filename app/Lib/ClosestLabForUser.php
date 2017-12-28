<?php

namespace App\Lib;

use App\Models\User;
use App\Models\AvailableLab;

class ClosestLabForUser
{
    public $closest_lab = null;
    public $distance = 1000;

    function __construct(\App\Models\User $user)
    {
        $this->findNearestLabForUser($user);
    }

    private function boundingBoxForUser($user)
    {
        if (empty($user->longitude) || empty($user->latitude)) {
            throw new Exception('User ' . $user->id . ' has not been geocoded.');
        }

        // we're simply adding a degree all around, which gives it approximately a
        // 55 mile radius

        $longitude_upper = $user->longitude < 0 ? $user->longitude + 1 : $user->longitude - 1;
        $longitude_lower = $user->longitude < 0 ? $user->longitude - 1 : $user->longitude + 1;
        $latitude_upper = $user->latitude < 0 ? $user->latitude - 1 : $user->latitude + 1;
        $latitude_lower = $user->latitude < 0 ? $user->latitude + 1 : $user->latitude - 1;

        $box = [
            'longitude_range' => [$longitude_upper, $longitude_lower],
            'latitude_range' => [$latitude_upper, $latitude_lower]
        ];

        return $box;
    }

    public function findNearestLabForUser($user)
    {
        $box = $this->boundingBoxForUser($user);

        $labs = AvailableLab::where('longitude', '>', $box['longitude_range'][1])
                    ->where('longitude', '<', $box['longitude_range'][0])
                    ->where('latitude', '>', $box['latitude_range'][1])
                    ->where('latitude', '<', $box['latitude_range'][0])
                    ->get();

        $user_geopoint = new Geopoint($user->latitude, $user->longitude);

        foreach ($labs as $lab) {
            $geo = new Geopoint($lab->latitude, $lab->longitude);

            $distance = $user_geopoint->distanceToPoint($geo, 'mi');

            if ($distance < $this->distance) {
                $this->distance = $distance;
                $this->closest_lab = $lab;
            }
        }
    }
}
