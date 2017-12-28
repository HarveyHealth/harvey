<?php

namespace App\Lib\Clients;

use App\Models\LabOrder;

class Shippo extends BaseClient
{
    protected $base_endpoint = 'https://api.goshippo.com/';

    public function enableWebhook(string $carrier, string $tracking_number)
    {
        $headers = ['Authorization' => 'ShippoToken '. config('services.shippo.key')];

        return $this->post('tracks/', compact('carrier', 'tracking_number'), $headers);
    }

    public static function isUsingTestKey() {
        return str_contains(config('services.shippo.key'), 'test');
    }
}
