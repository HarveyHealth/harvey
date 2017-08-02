<?php

namespace App\Lib;

use App\Lib\Clients\Geocoder;

class ZipCodeValidator
{
    protected $geocoder;
    protected $zip;
    protected $state = null;
    protected $unserviceable_states = [
        'AL', 'FL', 'NY', 'SC', 'TN', 'AK', 'HI', 'OR', 'AZ', 'CO', 'MT',
        'UT', 'KS', 'MN', 'ND', 'CT', 'ME', 'MD', 'MA', 'NH', 'PA', 'VT', 'DC'
    ];

    public function __construct(Geocoder $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function usaQuery()
    {
        return "{$this->getZip()} USA";
    }

    public function getState()
    {
        $this->callGeocoder();
        return $this->state;
    }

    protected function callGeocoder()
    {
        $result = $this->geocoder->geocode($this->usaQuery());
        return $this->state = $result['address']['state'];
    }

    public function isServiceable()
    {
        return $this->stateIsServiceable($this->getState());
    }

    protected function stateIsServiceable($state)
    {
        return !$this->stateIsUnserviceable($state);
    }

    protected function stateIsUnserviceable($state)
    {
        // If the state is in the unserviceable list or if no state is returned
        return in_array($state, $this->unserviceable_states) || empty($state);
    }
}
