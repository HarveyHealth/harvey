<?php

namespace App\Lib\Clients;

use App\Models\LabOrder;

class Shippo extends BaseClient
{
    protected $base_endpoint = 'https://api.goshippo.com/';

    public function enableWebhook(string $carrier, string $tracking_number)
    {
        $headers = ['Authorization' => 'ShippoToken '. config('services.shippo.key')];

        return $this->post('tracks/', ['form_params' => compact('carrier', 'tracking_number'), 'headers' => $headers]);
    }

    public static function isUsingTestKey() {
        return str_contains(config('services.shippo.key'), 'test');
    }
}
