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

            $data['pending_tests'] = $this->tests->model->pending();
            $data['recent_tests'] = $this->tests->model->recent(10);
            $data['upcoming_appointments'] = $this->appointments->model->pending();
            $data['recent_appointments'] = $this->appointments->model->recent(10);

        } else if ($user->user_type == 'patient') {

            $data['pending_tests'] = $this->tests->model->pending()->forPatient($user_id);
            $data['recent_tests'] = $this->tests->model->recent()->forPatient($user_id);
            $data['upcoming_appointments'] = $this->appointments->model->pending()->forPatient($user_id);
            $data['recent_appointments'] = $this->appointments->model->recent()->forPatient($user_id);

        } else if ($user->user_type == 'practitioner') {

            $data['pending_tests'] = $this->tests->model->pending()->forPractitioner($user_id);
            $data['recent_tests'] = $this->tests->model->recent(10)->forPractitioner($user_id);
            $data['upcoming_appointments'] = $this->appointments->model->pending()->forPractitioner($user_id);
            $data['recent_appointments'] = $this->appointments->model->recent(10)->forPractitioner($user_id);

        } else {
            abort(403);
        }

        return view('dashboard.index', $data);
    }
}
