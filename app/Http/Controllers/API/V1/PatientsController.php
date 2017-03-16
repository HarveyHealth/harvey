<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Patient;
use App\Transformers\V1\PatientTransformer;
use Illuminate\Http\Request;

class PatientsController extends BaseAPIController
{
    protected $transformer;
    
    public function __construct(PatientTransformer $transformer)
    {
        $this->transformer = $transformer;
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
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Patient $patient)
    {
        if (auth()->user()->can('view', $patient)) {
            return fractal()->item($patient)
                ->transformWith($this->transformer)
                ->respond();
        }  else {
            return $this->respondNotAuthorized('Unauthorized to view this resource');
        }
    }
    
    /**
     * @param Request $request
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
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
}
