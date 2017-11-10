<?php

namespace App\Lib\Clients;

class BaseClient
{
    protected $base_endpoint;
    protected $client;
    protected $headers = [];
    protected $params = [];

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
            'http_errors' => false,
        ];
    }

    public function get($call, $params = [], $headers = [])
    {
        $client = $this->client();

        $data = [];
        $data['query'] = array_merge($params, $this->params);
        $data['headers'] = array_merge($this->headers, $headers);

        $response = $client->get($this->baseEndpoint($call), $data);

        return $response;
    }

    public function post($call, $params = [], $headers = [])
    {
        $client = $this->client();

        $data = [];
        $data['body'] = array_merge($params, $this->params);;
        $data['headers'] = array_merge($this->headers, $headers);

        $response = $client->post($this->baseEndpoint($call), $data);

        return $response;
    }

    public function put($call, $params = [], $headers = [])
    {
        $client = $this->client();

        $data = [];
        $data['body'] = array_merge($params, $this->params);;
        $data['headers'] = array_merge($this->headers, $headers);

        $response = $client->put($this->baseEndpoint($call), $data);

        return $response;
    }

    protected function baseEndpoint($call)
    {
        $endpoint = trim($this->base_endpoint, '/') . '/';
        return $endpoint . $call;
    }
}
