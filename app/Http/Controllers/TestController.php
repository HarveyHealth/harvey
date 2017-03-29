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

        $practitioner->availability();

        $availability = new \App\Lib\PractitionerAvailability($practitioner);
        print_r($availability->availability());

        // $start = new \Carbon('2017-04-03 17:00:00', 'UTC');
        // echo $start->toTimeString() . PHP_EOL;
        // $start->tz = 'America/Los_Angeles';
        // echo $start->toTimeString();
        //
        // $date = \Carbon::now();
        // $week_of_year = $date->weekOfYear;
        // $year = $date->year;
        //
        // $start_date = \Carbon::createFromTimestamp(strtotime("{$year}W{$week_of_year}"));
        // $end_date = clone $start_date;
        // $end_date->addDays(7)->subSeconds(1);
        //
        // $week = \Carbon::now()->weekOfYear;
        // echo $week;
        //
        // echo $start_date->toDateTimeString() . '<br />';
        // echo $end_date->toDateTimeString();
        //
    }
}
