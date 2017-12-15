<?php

namespace App\Lib\Clients;

use GuzzleHttp\Client;

class BaseClient
{
    protected $base_endpoint;
    protected $client;
    protected $headers = [];
    protected $params = [];

    public function __construct($client = null)
    {
        $this->client = $client ?? $client = new Client(['defaults' => $this->defaults()]);;
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

    public function post($call, $body = '', $headers = [])
    {
        $data = [];
        $data['body'] = $body;
        $data['headers'] = array_merge($this->headers, $headers);

        return $this->client->post($this->baseEndpoint($call), $data);
    }

    public function put($call, $body = '', $headers = [])
    {
        $data = [];
        $data['body'] = $body;
        $data['headers'] = array_merge($this->headers, $headers);

        $response = $this->client->put($this->baseEndpoint($call), $data);

        return $response;
    }

    protected function baseEndpoint($call)
    {
        return trim($this->base_endpoint, '/') . "/{$call}";
    }
}
