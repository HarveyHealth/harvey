<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Appointment;
use App\Transformers\V1\AppointmentTransformer;
use Crell\ApiProblem\ApiProblem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Validator;

class AppointmentsController extends BaseAPIController
{
    /**
     * AppointmentsController constructor.
     * @param AppointmentTransformer $transformer
     */
    public function __construct(AppointmentTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $appointments = auth()->user()->appointments;

        return $this->transformedResponse($appointments);
    }

    /**
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Appointment $appointment)
    {
        if (auth()->user()->can('view', $appointment)) {
            return $this->transformedResponse($appointment);
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to view the appointment with id {$appointment->id}.");
            return $this->respondNotAuthorized($problem);
        }
    }

    /**
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_at' => 'required',
            'reason_for_visit' => 'required',
            'practitioner_id' => 'required'
        ]);

        if ($validator->fails()) {
            $problem = new ApiProblem();
            $problem->setDetail($validator->errors()->first());
            return $this->respondBadRequest($problem);
        }

        $appointment = new Appointment($request->all());

        if (auth()->user()->can('create', $appointment)) {
            $patient = auth()->user()->patient;
            $patient->appointments()->save($appointment);

            return $this->transformedResponse($appointment);
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to schedule a new appointment.");
            return $this->respondNotAuthorized($problem);
        }
    }
    
    public function delete(Appointment $appointment)
    {
        if (auth()->user()->can('delete', $appointment) && $appointment->isNotLocked()) {
            $appointment->delete();
            
            return fractal()->item($appointment)
                ->addMeta(['deleted' => true])
                ->withResourceName('appointments')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->respond(410);
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to cancel this appointment.");
            return $this->respondNotAuthorized($problem);
        }
    }
}
