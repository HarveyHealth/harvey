<?php

namespace App\Lib;

use App\Lib\Clients\Geocoder;
use App\Models\License;

class ZipCodeValidator
{
    protected $geocoder;
    protected $zip;
    protected $state = null;
    protected $unserviceableStates = [
        'AL', 'FL', 'NY', 'SC', 'TN'
    ];
    protected $regulatedStates = [
        'AK', 'HI', 'OR', 'AZ', 'CO', 'MT', 'UT', 'KS', 'MN',
        'ND', 'CT', 'ME', 'MD', 'MA', 'NH', 'PA', 'VT', 'DC'
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
        if (
        empty($state) ||
        in_array($state, $this->unserviceableStates) ||
        (in_array($state, $this->regulatedStates) && !License::all()->pluck('state')->contains($state))) {
            return true;
        }

        return false;
    }
}
