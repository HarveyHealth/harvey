<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{License};
use App\Lib\{TimeInterval, ZipCodeValidator};
use Illuminate\Http\Request;
use ResponseCode;

class ZipVerificationController extends BaseAPIController
{
    public function __construct(ZipCodeValidator $zipCodeValidator)
    {
        $this->zipCodeValidator = $zipCodeValidator;
    }

    public function getInfo(Request $request, string $zip)
    {
      // Grab geocoding information from zip code
      $this->zipCodeValidator->setZip($zip);
      $city = $this->zipCodeValidator->getCity();
      $state = $this->zipCodeValidator->getState();
      $is_serviceable = $this->zipCodeValidator->isServiceable($state);
      $practitioners = count(License::where('state', $state)->first());
      $is_regulated = $this->zipCodeValidator->isRegulated($state);

      return response()->json(compact('city', 'practitioners', 'is_regulated', 'is_serviceable', 'state', 'zip'));
    }
  }
