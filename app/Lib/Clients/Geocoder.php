<?php

namespace App\Lib\Clients;

use App\Lib\Slack;
use App\Notifications\SlackNotification;

class Geocoder extends BaseClient
{
    protected $base_endpoint = 'https://maps.googleapis.com/maps/api/';

    public function __construct()
    {
        parent::__construct();
        $base_params = [
            'key' => config('services.google_geocoder.api_key')
        ];
    }

    public function geocode($query)
    {
        try {
            $response = $this->get('geocode/json', ['address' => $query]);
        } catch (Exception $e) {
            $message = 'Exception processing geocoding: ' . $query;

            // log it
            \Log::warning($message);

            // slack it
            ops_error($message);

            return false;
        }

        if ($response->getStatusCode() != 200) {
            $message = 'Could not process geocoding: ' . $query;

            // log it
            \Log::warning($message);

            // slack it
            ops_error($message);

            return false;
        }

        $obj = json_decode($response->getBody());

        $result = $obj->results[0];

        $city = '';
        $state = '';
        $zip = '';
        $country = '';

        foreach ($result->address_components as $component) {
            if (in_array('locality', $component->types)) {
                $city = $component->long_name;
            }
            if (in_array('administrative_area_level_1', $component->types)) {
                $state = $component->short_name;
            }
            if (in_array('country', $component->types)) {
                $country = $component->short_name;
            }
            if (in_array('postal_code', $component->types)) {
                $zip = $component->long_name;
            }
        }

        $geo_data = [
            'address' => [
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'country' => $country,
            ],

            'location' => [
                'longitude' => $obj->results[0]->geometry->location->lng,
                'latitude' => $obj->results[0]->geometry->location->lat,
            ]
        ];

        return $geo_data;
    }
}
