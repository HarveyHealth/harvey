<?php

namespace App\Lib\Clients;

use GuzzleHttp\Client;
use TypeError;

class BaseClient
{
    protected $base_endpoint;
    protected $client;
    protected $headers = [];
    protected $params = [];

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client(['defaults' => $this->defaults()]);
    }

    protected function defaults()
    {
        return [
            'timeout' => 10,
            'http_errors' => false,
        ];
    }

    public function get(string $call, array $params = [], array $headers = [])
    {
        $data['query'] = array_merge($params, $this->params);
        $data['headers'] = array_merge($this->headers, $headers);

        return $this->client->get($this->baseEndpoint($call), $data);
    }

    public function post(string $call, array $opts = [])
    {
        return $this->client->post($this->baseEndpoint($call), $this->arrangeData($opts));
    }

    public function put(string $call, array $opts = [])
    {
        return $this->client->put($this->baseEndpoint($call), $this->arrangeData($opts));
    }

    protected function baseEndpoint(string $call)
    {
        return trim($this->base_endpoint, '/') . "/{$call}";
    }

    protected function arrangeData(array $opts = [])
    {
        foreach ($opts as $key => $value) {
            if (!is_array($value)) {
                throw new TypeError("BaseClient@arrangeData: '{$key}' value should be an Array.");
            }
        }

        if (!empty($opts['body_data'])) {
            $data['body'] = json_encode($opts['body_data'], JSON_FORCE_OBJECT);
        }

        if (!empty($form_params = array_merge($opts['form_params'] ?? [], $this->params))) {
            $data['form_params'] = $form_params;
        }

        if (!empty($headers = array_merge($opts['headers'] ?? [], $this->headers))) {
            $data['headers'] = $headers;
        }

        return $data ?? [];
    }

}
