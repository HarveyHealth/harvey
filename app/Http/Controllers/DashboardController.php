<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AppointmentRepository;
use App\Repositories\TestRepository;

class DashboardController extends Controller
{
    protected $tests;
    protected $appointments;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TestRepository $tests, AppointmentRepository $appointments)
    {
        $this->middleware('auth');

        $this->tests = $tests;
        $this->appointments = $appointments;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        $user = auth()->user();

        if ($user->user_type == 'admin') {

            $data['pending_tests'] = $this->tests->pending()->get();
            $data['recent_tests'] = $this->tests->recent(10)->get();
            $data['upcoming_appointments'] = $this->appointments->pending()->get();
            $data['recent_appointments'] = $this->appointments->recent(10)->get();

        } else if ($user->user_type == 'patient') {

            $data['pending_tests'] = $this->tests->pending()->forPatient($user->id)->get();
            $data['recent_tests'] = $this->tests->recent()->forPatient($user->id)->get();
            $data['upcoming_appointments'] = $this->appointments->pending()->forPatient($user->id)->get();
            $data['recent_appointments'] = $this->appointments->recent()->forPatient($user->id)->get();

        } else if ($user->user_type == 'practitioner') {

            $data['pending_tests'] = $this->tests->pending()->forPractitioner($user->id)->get();
            $data['recent_tests'] = $this->tests->recent(10)->forPractitioner($user->id)->get();
            $data['upcoming_appointments'] = $this->appointments->pending()->forPractitioner($user->id)->get();
            $data['recent_appointments'] = $this->appointments->recent(10)->forPractitioner($user->id)->get();

        } else {
            abort(403);
        }

        return view('dashboard.index', $data);
    }
}
