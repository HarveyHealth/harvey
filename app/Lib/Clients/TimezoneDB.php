<?php

namespace App\Lib\Clients;

use App\Lib\Slack;
use App\Notifications\SlackNotification;

class TimezoneDB extends BaseClient
{
    protected $base_endpoint = 'http://api.timezonedb.com/v2/';

    public function __construct()
    {
        parent::__construct();
        $base_params = [
            'format' => 'json',
            'key' => config('services.timezonedb.key')
        ];

        $this->params = array_merge($base_params, $this->params);
    }

    public function timezoneForLongitudeAndLatitude($longitude, $latitude)
    {
        $params = [
            'by' => 'position',
            'lng' => $longitude,
            'lat' => $latitude
        ];

        $response = $this->get('get-time-zone',$params);

        if ($response->getStatusCode() != 200) {
            $message = 'Could not process timezone for longitude and latitude: ' . $longitude . ', ' . $latitude;

            // log it
            \Log::warning($message);

            // slack it
            (new Slack)->notify(new SlackNotification('*[Warning]* ' . $message, 'engineering'));

            return false;
        }

        $tz_data = json_decode($response->getBody());
        $zone = $tz_data->zoneName;

        if (empty($zone)) {
            $message = 'Could not process timezone for longitude and latitude: ' . $longitude . ', ' . $latitude;

            // log it
            \Log::warning($message);

            // slack it
            (new Slack)->notify(new SlackNotification('*[Warning]* ' . $message, 'engineering'));

            return false;
        }

        return $zone;
    }
}
