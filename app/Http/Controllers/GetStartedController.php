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
          $patientId = Patient::where('user_id', Auth::id())->first()->id;
          if (!$patientId) {
            return redirect(route('dashboard'));
          }
          // If the user is logged in as a patient and has an appointment, route to dashboard
          $hasAppointment = Appointment::where('patient_id', $patientId)->first();
          if ($hasAppointment) {
            return redirect(route('dashboard'));
          }
        }
        return view('pages.getstarted');
    }
}
