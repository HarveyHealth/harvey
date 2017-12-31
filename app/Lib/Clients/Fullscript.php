<?php

namespace App\Lib\Clients;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use Exception;

class Fullscript extends BaseClient
{
    protected $base_endpoint;

    public function __construct(Client $client = null)
    {
        parent::__construct($client);
        $this->headers = [
            'X-API-Key' => config('services.fullscript.api_key'),
            'X-FS-Clinic-Key' => config('services.fullscript.clinic_key'),
            'Content-Type' => 'application/json'
        ];
        $this->base_endpoint = config('services.fullscript.api_host');

        if (empty($this->base_endpoint)) {
            throw new Exception('Fullscript API Host not set, please check your ".env" or ".env.testing" file.');
        }
    }

    /**
     * List Patients by email or external_ref
     */
    public function getPatients($external_ref = '', $email = '', $limit = null, $page = null)
    {
        // build arguments array
        $params = [];
        $params = array_filter(compact('external_ref', 'email', 'limit', 'page'));

        //perform call to API

        try {
            $response = $this->get('patients', $params);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            if ($response->getStatusCode() == 400) {
                throw new FullscriptClientException('bad input parameter', $response->getStatusCode());
            } else {
                $message = $e->getMessage();
                $body = json_decode($response->getBody());
                if (isset($body->error)) {
                    $message = $body->error;
                }
                // Exceeded rate limit 429
                throw new FullscriptClientException($message, $response->getStatusCode());
            }
        }


        return json_decode($response->getBody());
    }

    /**
    * Update an existing patient
    * @param array $data
    * @return array the updated patient
    */
    public function updatePatient($id, $data)
    {
        //perform call to API

        try {
            $response = $this->put("patients/{$id}", json_encode($data, JSON_FORCE_OBJECT));
        } catch (ClientException $e) {
            $response = $e->getResponse();
            switch ($response->getStatusCode()) {
                case 400:
                    throw new FullscriptClientException('Invalid UUID supplied', $response->getStatusCode());
                    break;
                case 404:
                    throw new FullscriptClientException('Patient not found', $response->getStatusCode());
                    break;
                case 405:
                    throw new FullscriptClientException('Validation exception', $response->getStatusCode());
                    break;

                default:
                    $message = $e->getMessage();
                    $body = json_decode($response->getBody());
                    if (isset($body->error)) {
                        $message = $body->error;
                    }
                    // Exceeded rate limit 429
                    throw new FullscriptClientException($message, $response->getStatusCode());
            }
        }

        return json_decode($response->getBody());
    }
    /**
     * Creates a Patient
     * @param array $data
        *  "first_name": "Chris",
        *  "last_name": "Wise",
        *  "email": "chris@fullscript.com",
        *  "date_of_birth": "1971-03-15",
        *  "external_ref": "9991-2233-1222"
     */
    public function createPatient($data)
    {
        //perform call to API

        try {
            $response = $this->post('patients', $data);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            switch ($response->getStatusCode()) {
                case 405:
                    throw new FullscriptClientException('Invalid Input', $response->getStatusCode());
                    break;
                default:
                    $message = $e->getMessage();
                    $body = json_decode($response->getBody());
                    if (isset($body->error)) {
                        $message = $body->error;
                    }
                    // Exceeded rate limit 429
                    throw new FullscriptClientException($message, $response->getStatusCode());
            }
        }

        return json_decode($response->getBody());
    }
}

class FullscriptClientException extends Exception
{
}
