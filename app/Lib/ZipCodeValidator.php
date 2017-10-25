<?php

namespace App\Lib;

use App\Lib\Clients\Geocoder;
use App\Models\License;
use Cache;
use use Illuminate\Support\Facades\Redis;

class ZipCodeValidator
{
    protected $city, $geocoder, $zip, $state = null;

    protected $unserviceableStates = [
        'AL', 'FL', 'NY', 'SC', 'TN'
    ];
    // Updated: 08/22/2017
    // This is a hotfix and should be included in the backend logic when determining which
    // practitioners to send to the frontend
    protected $regulatedStates = [
      'AK', 'CA', 'HI', 'OR', 'WA', 'AZ', 'CO', 'MT', 'UT', 'KS', 'MN', 'ND', 'CT', 'ME', 'MD', 'MA', 'NH', 'PA', 'VT', 'DC'
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
        $redis_key = "state-for-zip-{$query}";
        $json_result = Redis::get($redis_key);

        if (empty($json_result)) {
            $json_result = json_encode($this->geocoder->geocode($query));
            Redis::set($redis_key, $json_result);
            Redis::expire($redis_key, TimeInterval::days(rand(10, 30))->addSeconds(rand(0, 100))->toSeconds());
        }

        $result = json_decode($json_result, true);

        $this->state = $result['address']['state'];
        $this->city = $result['address']['city'];

        $minutes_before_retry = TimeInterval::minutes(10)->toSeconds();

        if ((empty($this->state) || empty($this->city))
        && Redis::ttl($redis_key) > $minutes_before_retry) {
            Redis::expire($redis_key, $minutes_before_retry);
        }

        return $result;
    }

    public function isRegulated($state)
    {
        return in_array($state, $this->regulatedStates);
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
