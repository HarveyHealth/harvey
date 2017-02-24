<?php

namespace App\Lib\Clients;

class Geocoder extends BaseClient
{
    protected $base_endpoint = 'https://hhlocation.obcl.io/Service/geocode:';

    function __construct()
    {
        parent::__construct();
    }

    function geocode($query)
    {
        $response = $this->get('',['query' => $query]);

        if ($response->getStatusCode() != 200) {

            $message = 'Could not process geocoding: ' . $query;

            // log it
            \Log::warning($message);

            // slack it
            (new Slack)->notify(new SlackNotification('*[Warning]* ' . $message, 'engineering'));

            return false;
        }

        $geo = json_decode($response->getBody())->returnValue;

        $geo_data = [
            'address' => [
                'street_address' => $geo->address->Street ?? NULL,
                'city' => $geo->address->City ?? NULL,
                'state' => $geo->address->State ?? NULL,
                'zip' => $geo->address->ZIP ?? NULL,
                'country' => $geo->address->CountryCode ?? NULL,
            ],

            'location' => [
                'longitude' => $geo->location->longitude ?? NULL,
                'latitude' => $geo->location->latitude ?? NULL,
            ]
        ];

        return $geo_data;
    }
}
