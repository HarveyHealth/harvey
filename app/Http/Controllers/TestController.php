<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\TimeslotManager;
use App\Lib\PractitionerAvailability;

class TestController extends Controller
{
    public function index()
    {
        $practitioner = \App\Models\Practitioner::findOrFail(1);
        //
        // print_r($practitioner->schedule);

        $availability = new \App\Lib\PractitionerAvailability($practitioner);
        dd($availability->availability());

        $year = 2017;
        $week_of_year = \Carbon::now()->weekOfYear;

        $start_date = \Carbon::createFromTimestamp(strtotime("{$year}W{$week_of_year}"));
        $start_date->addDays(date('N', strtotime('Tuesday') - 1));

        echo $start_date->toDateTimeString();
    }
}
