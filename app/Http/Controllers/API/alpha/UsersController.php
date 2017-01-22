<?php

namespace App\Http\Controllers\API\alpha;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\AppointmentRepository;
use App\Http\Controllers\API\alpha\Transformers\UserTransformer;
use App\Http\Controllers\API\alpha\Transformers\AppointmentTransformer;

class UsersController extends BaseAPIController
{
    protected $transformer;
    protected $appointmentTransformer;
    protected $appointments;
    
    public function __construct(UserTransformer $transformer,
                                AppointmentTransformer $appointmentTransformer,
                                AppointmentRepository $appointments)
    {
        $this->transformer = $transformer;
        $this->appointmentTransformer = $appointmentTransformer;
        $this->appointments = $appointments;
    }
    
    public function index(Request $request)
    {
        // List multiple users
    }

    public function store(Request $request)
    {
        // I don't foresee the need to create multiple users at a time.
    }

    public function create(Request $request)
    {
        // This logic is in the RegisterController
    }

    public function show(User $user)
    {
        if (auth()->user()->can('view', $user)) {
            $transformedUser = $this->transformer->transform($user);
            return $this->respond($transformedUser);
        } else {
            return $this->respondNotAuthorized('Unauthorized to view this resource');
        }
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->except('api_token'));
        $transformedUser = $this->transformer->transform($user);
        return $this->respond($transformedUser);
    }
    
    public function appointments(User $user)
    {
        if (auth()->user()->cant('view', $user)) {
            return $this->respondNotAuthorized('Unauthorized to view this resource');
        }
        
        if ($user->user_type == 'patient') {
            $appointments = $this->appointments->forPatient($user->id)->get();
        } elseif ($user->user_type == 'practitioner') {
            $appointments = $this->appointments->forPractitioner($user->id)->get();
        } else {
            $appointments = $this->appointments->limit(10)->get();
        }
    
        $transformedAppointments = $this->appointmentTransformer
            ->transformCollection($appointments);
        
        return $this->respond($transformedAppointments);
    }
}
