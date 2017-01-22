<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\Repositories\AppointmentRepository;
use App\Repositories\TestRepository;

class DashboardController extends Controller
{
    protected $tests;
    protected $appointments;

    /**
     * DashboardController constructor.
     * @param TestRepository        $tests
     * @param AppointmentRepository $appointments
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
        $user = auth()->user();

        if ($user->user_type == 'admin') {
            $data = $this->adminDashboardData();
        } elseif ($user->user_type == 'patient') {
            $data = $this->patientDashboardData($user);
        } elseif ($user->user_type == 'practitioner') {
            $data = $this->practitionerDashboardData($user);
        } else {
            Log::error('Unable to gather dashboard data for user id: ' . auth()->id());
            abort(403);
        }

        if (request()->ajax()) {
            return response()->json($data);
        }

        return view('dashboard.index', $data);
    }

    protected function adminDashboardData()
    {
        return [
            'pending_tests' => $this->tests->pending()->get(),
            'recent_tests' => $this->tests->recent(10)->get(),
            'upcoming_appointments' => $this->appointments->pending()->get(),
            'recent_appointments' => $this->appointments->recent(10)->get()
        ];
    }

    protected function patientDashboardData($user)
    {
        return [
            'pending_tests' => $this->tests->pending()->forPatient($user->id)->get(),
            'recent_tests' => $this->tests->recent()->forPatient($user->id)->get(),
            'upcoming_appointments' => $this->appointments->pending()->forPatient($user->id)->get(),
            'recent_appointments' => $this->appointments->recent()->forPatient($user->id)->get()
        ];
    }

    protected function practitionerDashboardData($user)
    {
        return [
            'pending_tests' => $this->tests->pending()->forPractitioner($user->id)->get(),
            'recent_tests' => $this->tests->recent(10)->forPractitioner($user->id)->get(),
            'upcoming_appointments' => $this->appointments->pending()->forPractitioner($user->id)->get(),
            'recent_appointments' => $this->appointments->recent(10)->forPractitioner($user->id)->get()
        ];
    }

    public function getUser()
    {
        $user = auth()->user();
        $userJson = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'user_type' => $user->user_type,
            'email' => $user->email,
            'phone' => $user->phone,
            'payment_info'=> (bool) $user->stripe_customer_id ? true : false,
            'api_token' => $user->api_token
        ];
        return response()->json($userJson);
    }
}
