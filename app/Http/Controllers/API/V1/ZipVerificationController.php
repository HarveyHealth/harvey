<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{License};
use App\Lib\{TimeInterval, ZipCodeValidator};
use Illuminate\Http\Request;
use Redis, ResponseCode, Session;

class ZipVerificationController extends BaseAPIController
{
    public function __construct(ZipCodeValidator $zipCodeValidator)
    {
        $this->zipCodeValidator = $zipCodeValidator;
    }

    public function captureZip(Request $request, string $zip)
    {
      // Grab geocoding information from zip code
      $this->zipCodeValidator->setZip($zip);
      $city = $this->zipCodeValidator->getCity();
      $state = $this->zipCodeValidator->getState();
      $servicable = $this->zipCodeValidator->isServiceable($state);
      $practitioners = License::where('state', $state)->first();

      // Store zip code in Redis if serviceable and set to expire in a day if the user
      // never continues through to signup funnel
      if ($servicable) {
        $sessionId = Session::getId();
        $redisKey = "login-zip-{$sessionId}";
        Redis::set($redisKey, $zip);
        Redis::expire($redisKey, TimeInterval::day()->toSeconds());
      }

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
