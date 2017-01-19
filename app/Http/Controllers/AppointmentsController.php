<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class AppointmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'selectedDate' => 'required',
            'selectedTime' => 'required',
            'details' => 'required'
        ]);
        
        $date_selected = $request->selectedDate;
        $carbon_date = Carbon::parse($date_selected);
        $carbon_date->hour = $request->selectedTime;
        
        $appointment = new Appointment();
        $appointment->patient_user_id = \Auth::user()->id;
        $appointment->practitioner_user_id = User::whereUserType('practitioner')->first()->id;
        $appointment->appointment_at = $carbon_date;
        $appointment->reason_for_visit = $request->details ?: 'None Given.';
        $appointment->save();
        
        $response = [
            'id' => $appointment->id,
            'appointment_at' => $appointment->appointment_at,
            'reason_for_visit' => $appointment->reason_for_visit
        ];
        
        return response()->json($response);
    }
}
