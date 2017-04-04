<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\Practitioner;
use App\Lib\PractitionerAvailability as Availability;

class PractitionerAvailability extends BaseAPIController
{
    public function show(Practitioner $practitioner)
    {
        return $practitioner->availability();
    }
}
