<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Lib\Geopoint;
use App\Lib\ClosestLabForUser;

class TestController extends Controller
{
    public function index()
    {
    	$user = User::find(52);
        $user->latitude = 33.977942;
        $user->longitude = -118.434399;

        $closest_lab = new ClosestLabForUser($user);

        $patient = $user->patient;
        $patient->available_lab_id = $closest_lab->closest_lab->id;
        $patient->lab_distance = $closest_lab->distance;

        $lab = $patient->lab;
	}
}
