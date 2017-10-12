<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Appointment, Patient};
use Auth;

class GetStartedController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
          // If user is logged in and is not a patient, route to dashboard
          if (currentUser()->isNotPatient()) {
            return redirect(route('dashboard'));
          }

          if (currentUser()->appointments()->first()) {
            return redirect(route('dashboard'));
          }
        }
        return view('pages.getstarted');
    }
}
