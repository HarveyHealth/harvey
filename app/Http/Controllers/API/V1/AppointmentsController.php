<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Appointment;
use App\Transformers\V1\AppointmentTransformer;
use Illuminate\Http\Request;
use \Validator;

class AppointmentsController extends BaseAPIController
{
    private $transformer;
    
    public function __construct(AppointmentTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
            return $this->respondNotAuthorized('Unauthorized to create this resource');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
