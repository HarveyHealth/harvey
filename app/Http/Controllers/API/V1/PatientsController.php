<?php

namespace App\Http\Controllers\API\V1;

use App\Transformers\V1\PatientTransformer;
use App\Transformers\V1\AppointmentTransformer;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientsController extends BaseAPIController
{
    protected $transformer;
    protected $appointmentTransformer;
    
    public function __construct(PatientTransformer $transformer,
                                AppointmentTransformer $appointmentTransformer)
    {
        $this->transformer = $transformer;
        $this->appointmentTransformer = $appointmentTransformer;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Patient $patient)
    {
        $validator = \Validator::make($filtered_array, [
            'birthdate' => 'date',
            'height_feet' => 'numeric|between:1,10',
            'height_inches' => 'numeric|between:1,11',
            'weight' => 'integer',
            'symptoms' => 'json'
        ]);
    
        if ($validator->fails()) {
            return $this->respondUnprocessable($validator->messages());
        }
    }
    
    /**
     * @api {get} /patient/:patient_id/appointments View appointments for a specific patient
     * @apiName ViewPatientAppointments
     * @apiGroup Appointment
     *
     * @apiParam {Number} id Patient id
     * */
    public function appointments(Patient $patient)
    {
        $appointments = $patient->appointments;
     
        return fractal()->collection($appointments)
                    ->transformWith($this->appointmentTransformer)
                    ->toArray();
    }
}
