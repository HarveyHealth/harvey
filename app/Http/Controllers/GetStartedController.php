<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Appointment, Patient};
use Auth;

class GetStartedController extends Controller
{
    public function index()
    {
        if (currentUser() && (currentUser()->isNotPatient() || currentUser()->has_an_appointment)) {
            return redirect(route('dashboard'));
          }

        return view('pages.getstarted');
    }
}
