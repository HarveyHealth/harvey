<?php

namespace App\Lib;

use App\Lib\Clients\Geocoder;
use App\Models\License;
use Cache;

use Illuminate\Support\Facades\Redis;

class ZipCodeValidator
{
    protected $city, $geocoder, $zip, $state = null;

    protected $unserviceable_states = [
        'AL', 'FL', 'NY', 'SC', 'TN',
    ];
    // Updated: 08/22/2017
    // This is a hotfix and should be included in the backend logic when determining which
    // practitioners to send to the frontend
    protected $regulated_states = [
      'AK', 'CA', 'HI', 'OR', 'WA', 'AZ', 'CO', 'MT', 'UT', 'KS', 'MN', 'ND', 'CT', 'ME',
      'MD', 'NH', 'VT', 'DC',
    ];

    protected $unregulated_states = [
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

    public static function getStatesCollection()
    {
        return Cache::remember('states_list', TimeInterval::day()->toMinutes(), function () {
            return collect(array_keys(json_decode(file_get_contents(public_path() . '/states.json'), true)));
        });
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

    public function isRegulated($state)
    {
        return in_array($state, $this->regulated_states);
    }

    public function isServiceable(string $state = null)
    {
        return $this->stateIsServiceable($state ?? $this->getState());
    }

    protected function stateIsServiceable($state)
    {
        if (
        empty($state) ||
        in_array($state, $this->unserviceable_states) ||
        (in_array($state, $this->regulated_states) && !License::where('state', $state)->first())) {
            return false;
        }

        return true;
    }
}
