<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Appointment;
use App\Transformers\V1\AppointmentTransformer;
use Illuminate\Http\Request;
use \Validator;

class AppointmentsController extends BaseAPIController
{
    /**
     * @var AppointmentTransformer
     */
    private $transformer;
    
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
        
        return fractal()->collection($appointments)
            ->withResourceName('appointments')
            ->transformWith($this->transformer)
            ->serializeWith($this->serializer)
            ->respond();
    }
    
    /**
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Appointment $appointment)
    {
        if (auth()->user()->can('view', $appointment)) {
            return fractal()->item($appointment)
                ->withResourceName('appointments')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        } else {
            $this->problem->setDetail("You do not have access to view the appointment with id {$appointment->id}.");
            return $this->respondNotAuthorized();
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
            $this->problem->setDetail($validator->errors()->first());
            return $this->respondBadRequest($validator);
        }
        
        $appointment = new Appointment($request->all());
        
        if (auth()->user()->can('create', $appointment)) {
            $patient = auth()->user()->patient;
            $patient->appointments()->save($appointment);
            
            return fractal()->item($appointment)
                ->withResourceName('appointments')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        } else {
            $this->problem->setDetail("You do not have access to schedule a new appointment.");
            return $this->respondNotAuthorized();
        }
    }
}
