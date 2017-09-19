<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{License};
use App\Http\Controllers\Controller;
use App\Lib\{TimeInterval, ZipCodeValidator};
use Illuminate\Http\Request;
use Exception, ResponseCode;

class ZipVerificationController extends BaseAPIController
{
    public function __construct(ZipCodeValidator $zipCodeValidator)
    {
        $this->zipCodeValidator = $zipCodeValidator;
    }

    public function captureZip($zip)
    {
      // Grab geocoding information from zip code
      $this->zipCodeValidator->setZip($zip);
      $city = $this->zipCodeValidator->getCity();
      $state = $this->zipCodeValidator->getState();
      $servicable = $this->zipCodeValidator->isServiceable($state);
      $practitioners = License::all()->pluck('state')->contains($state);

      return response()->json([
        'city' => $city,
        'practitioners' => count($practitioners),
        'regulated' => $this->zipCodeValidator->isRegulated($state),
        'serviceable' => $servicable,
        'state' => $state,
        'zip' => $zip,
      ]);
    }
  }
