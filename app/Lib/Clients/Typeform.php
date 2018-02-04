<?php

namespace App\Lib\Clients;

use App\Lib\TimeInterval;
use Cache, Exception;

class Typeform extends BaseClient
{
    public function __construct()
    {
        $this->base_endpoint = 'https://api.typeform.com/v1/form';
        parent::__construct();
    }

    public function get(string $token, array $params = [], array $headers = [])
    {
        if (empty(config('services.typeform.uid')) || empty(config('services.typeform.api_key'))) {
            throw new Exception('Typeform API credentials not found.');
        }

        $params = [
            'key' => config('services.typeform.api_key'),
            'token' => $token,
        ];

        return parent::get(config('services.typeform.uid'), $params, $headers);
    }

    public static function getDataForToken(string $token)
    {
        if (empty($token)) {
            return [];
        }

        $key = "intake-token-{$token}-data";

        $output = Cache::remember($key, TimeInterval::weeks(1)->toMinutes(), function () use ($token) {
            $response = json_decode((new self)->get($token)->getBody()->getContents(), true);

            if (empty($response['responses'][0]['token']) || 200 != $response['http_status']) {
                return [];
            }

            return array_intersect_key($response, array_flip(['questions', 'responses']));
        });

        if (empty($output)) {
            Cache::put($key, $output, TimeInterval::hours(3)->toMinutes());
        }

        return $output;
    }
}
