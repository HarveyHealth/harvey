<?php

namespace App\Lib;

use App\Lib\Clients\Geocoder;
use App\Models\License;
use Illuminate\Support\Facades\Redis;
use Cache;

class ZipCodeValidator
{
    protected $city, $geocoder, $zip, $state = null;

    const UNSERVICEABLE_STATES = [
        'AL', 'FL', 'NY', 'SC', 'TN',
    ];

    const REGULATED_STATES = [
      'AK', 'CA', 'HI', 'OR', 'WA', 'AZ', 'CO', 'MT', 'UT', 'KS', 'MN', 'ND', 'CT', 'ME',
      'MD', 'NH', 'VT', 'DC',
    ];

    const UNREGULATED_STATES = [
        'AR', 'DE', 'GA', 'ID', 'IL', 'IN', 'IA', 'KY', 'LA', 'MA', 'MI', 'MS', 'MO', 'NE',
        'NV', 'NJ', 'NM', 'NC', 'OH', 'OK', 'PA', 'RI', 'SD', 'TX', 'VA', 'WV', 'WI', 'WY'
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

        $seconds_before_retry = TimeInterval::minutes(10)->toSeconds();

        if ((empty($this->state) || empty($this->city))
        && Redis::ttl($redis_key) > $seconds_before_retry) {
            Redis::expire($redis_key, $seconds_before_retry);
        }

        return $result;
    }

    public function isRegulated()
    {
        return in_array($this->getState(), self::REGULATED_STATES);
    }

    public function isNotServiceable()
    {
        $state = $this->getState();
        return empty($state) || in_array($state, self::UNSERVICEABLE_STATES) || ($this->isRegulated() && !License::whereState($state)->first());
    }

    public function isServiceable()
    {
        return !$this->isNotServiceable();
    }
}
