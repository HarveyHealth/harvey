<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\TimeslotManager;
use App\Lib\PractitionerAvailability;

class TestController extends Controller
{
    public function index()
    {
        $geo = new \App\Lib\Clients\Geocoder;
        $res = $geo->geocode('15344 mystic rock drive, carmel');

        print_r($res);


    }
}
