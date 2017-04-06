<?php

namespace App\Lib\Clients;

class BaseClient
{
    protected $base_endpoint;
    protected $client;
    protected $headers = [];

    public function __construct($client = null)
    {
        if (empty($client)) {
            $client = new \GuzzleHttp\Client(['defaults' => $this->defaults()]);
        }

        $this->client = $client;
    }

    protected function client()
    {
        return $this->client;
    }

    protected function defaults()
    {
        return [
            'timeout' => 10,
            'exceptions' => false,
        ];
    }

    public function get($call, $params = [], $headers = [])
    {
        $client = $this->client();

        $data = [];
        $data['query'] = $params;
        $data['headers'] = array_merge($this->headers, $headers);

        $response = $client->get($this->baseEndpoint($call), $data);

        return $response;
    }

    public function post($call, $params = [], $headers = [])
    {
        $client = $this->client();

        $data = [];
        $data['body'] = $params;
        $data['headers'] = array_merge($this->headers, $headers);

        $response = $client->post($this->baseEndpoint($call), $data);

        return $response;
    }

    protected function baseEndpoint($call)
    {
        $endpoint = trim($this->base_endpoint, '/') . '/';
        return $endpoint . $call;
    }
}
