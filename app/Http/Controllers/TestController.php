<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\TimeslotManager;
use App\Lib\PractitionerAvailability;
use Twilio\Rest\Client as Twilio;

class TestController extends Controller
{
    public function index()
    {
        echo config('services.twilio.sid') . ' | ' . config('services.twilio.token');
        $twilio = new Twilio(config('services.twilio.sid'), config('services.twilio.token'));
    }
}
