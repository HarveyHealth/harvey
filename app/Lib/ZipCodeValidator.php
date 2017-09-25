<?php

namespace App\Lib;

use App\Lib\Clients\Geocoder;
use App\Models\License;
use Cache;

class ZipCodeValidator
{
    protected $city, $geocoder, $zip, $state = null;

    const UNSERVICEABLE_STATES = [
        'AL', 'FL', 'NY', 'SC', 'TN',
    ];

    const REGULATED_STATES = [
      'AK', 'CA', 'HI', 'OR', 'WA', 'AZ', 'CO', 'MT', 'UT', 'KS',
      'MN','ND', 'CT','ME', 'MD', 'MA', 'NH', 'PA', 'VT', 'DC',
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
        $query = "{$this->getZip()} USA";

        $result = Cache::remember("call-geocoder-{$query}", TimeInterval::months(1)->toMinutes(), function () use ($query) {
            return $this->geocoder->geocode($query);
        });

        $this->state = $result['address']['state'];
        $this->city = $result['address']['city'];

        if (empty($this->state) || empty($this->city)) {
            Cache::forget("call-geocoder-{$query}");
        }

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
        if (
        empty($state) ||
        in_array($state, self::UNSERVICEABLE_STATES) ||
        (in_array($state, self::REGULATED_STATES) && !License::all()->pluck('state')->contains($state))) {
            return true;
        }

        return false;
    }
}
