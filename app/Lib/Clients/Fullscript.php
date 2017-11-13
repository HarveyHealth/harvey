<?php

namespace App\Lib\Clients;
/**
 *
 */
class Fullscript extends BaseClient
{
    protected $base_endpoint = '';

    public function __construct($client = null)
    {
        parent::__construct($client);
        $this->headers = [
            'X-API-Key' => config('services.fullscript.api_key'),
            'X-FS-Clinic' => config('services.fullscript.clinic_key'),
        ];
        $this->base_endpoint = config('services.fullscript.api_host');
    }


    /**
     * List Patients by email or external_ref
     */
    public function getPatients($external_ref = '', $email = '', $limit = null, $page = null){
        // build arguments array
        $params = [];
        if (!empty($external_ref)){
            $params['external_ref'] = $external_ref;
        }
        if (!empty($email)){
            $params['email'] = $email;
        }
        if (!empty($limit)){
            $params['limit'] = $limit;
        }
        if (!empty($page)){
            $params['page'] = $page;
        }

        //perform call to API
        $response = $this->get('patients', $params);


        if ($response->getStatusCode() == 400) {
            throw new FullscriptClientException('bad input parameter');
        }

        return json_decode($response->getBody());
    }

    /**
    * Update an existing patient
    * @param array $data
    * @return array the updated patient
    */
    public function updatePatient($id, $data){
        //perform call to API
        $response = $this->put("patients/{$id}", $data);

        switch ($response->getStatusCode()) {
            case 400:
                throw new FullscriptClientException('Invalid UUID supplied');
                break;
            case 404:
                throw new FullscriptClientException('Patient not found');
                break;
            case 405:
                throw new FullscriptClientException('Validation exception');
                break;
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
    public function createPatient($data){
        //perform call to API
        $response = $this->post("patients", $data);


        if ($response->getStatusCode() == 405) {
            throw new FullscriptClientException('Invalid Input');
        }

        return json_decode($response->getBody());
    }


}

class FullscriptClientException extends \Exception{}
