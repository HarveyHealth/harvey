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
     * @param Request     $request
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'appointment_at' => 'required',
            'reason_for_visit' => 'required',
            'practitioner_id' => 'required'
        ]);
    
        if ($validator->fails()) {
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
            return $this->respondNotAuthorized('Unauthorized to create this resource.');
        }
    }
}
