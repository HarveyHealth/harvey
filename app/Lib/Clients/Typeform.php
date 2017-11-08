<?php

namespace App\Lib\Clients;

class Typeform extends BaseClient
{
    public function __construct()
    {
        $this->base_endpoint = 'https://api.typeform.com/v1/form';
        parent::__construct();
    }

    public function get(string $token, array $params = [], array $headers = [])
    {
        if (empty(config('services.typeform.uid')) || empty(config('services.typeform.api_key')) || empty($token)) {
            return null;
        }

        $params = [
            'key' => config('services.typeform.api_key'),
            'token' => $token,
        ];

        return parent::get(config('services.typeform.uid'), $params, $headers);
    }
}
