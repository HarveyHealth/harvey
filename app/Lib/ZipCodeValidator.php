<?php

namespace App\Lib;

use App\Lib\Clients\Geocoder;
use Cache;

class ZipCodeValidator
{
    protected $city, $geocoder, $zip, $state = null;

    protected $unserviceable_states = ['AL', 'FL', 'NY', 'SC', 'TN'];

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

    public function getCity()
    {
        $this->callGeocoder();
        return $this->city;
    }

    public function getState()
    {
        $this->callGeocoder();
        return $this->state;
    }

    protected function callGeocoder()
    {
        $query = $this->usaQuery();

        $result = Cache::remember("call-geocoder-{$query}", TimeInterval::weeks(1)->toMinutes(), function () use ($query) {
            return $this->geocoder->geocode($query);
        });

        $this->state = $result['address']['state'];
        $this->city = $result['address']['city'];

        return $result;
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
