<?php

namespace App\Lib\Clients;

class IntakeQ extends BaseClient
{
    protected $base_endpoint = 'https://intakeq.com/api/v1/';

    public function __construct()
    {
        parent::__construct();
        $this->headers = ['X-Auth-Key' => config('services.intakeq.api_key')];
    }

    public function getIntake($intake_id)
    {
        return $this->get('intakes/' . $intake_id);
    }
}
