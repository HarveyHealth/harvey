<?php

namespace App\Http\Traits;

use App\Lib\Geopoint;

trait Geocodable
{
    public function geopoint()
    {
        return new Geopoint($this->latitude, $this->longitude);
    }

    public function distanceToPoint(Geopoint $point)
    {
        return $this->geopoint()->distanceToPoint($point, 'mi');
    }
}
