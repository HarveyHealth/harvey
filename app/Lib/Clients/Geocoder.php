<?php

namespace App\Lib\Clients;

class Geocoder extends BaseClient
{
    protected $base_endpoint = 'https://hhlocation.obcl.io/Service/geocode:';

    public function __construct()
    {
        parent::__construct();
    }

    public function geocode($query)
    {
        $response = $this->get('', ['query' => $query]);

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
                'street_address' => $geo->address->Street ?? null,
                'city' => $geo->address->City ?? null,
                'state' => $geo->address->State ?? null,
                'zip' => $geo->address->ZIP ?? null,
                'country' => $geo->address->CountryCode ?? null,
            ],

            'location' => [
                'longitude' => $geo->location->longitude ?? null,
                'latitude' => $geo->location->latitude ?? null,
            ]
        ];

        return $geo_data;
    }
}
