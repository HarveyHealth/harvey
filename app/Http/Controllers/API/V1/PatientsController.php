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
        parent::__construct();
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
                ->withResourceName('patients')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->toArray();
        } else {
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
        if (auth()->user()->can('update', $patient)) {
            $patient->update($request->all());
        
            return fractal()->item($patient)
                ->withResourceName('patients')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        } else {
            return $this->respondNotAuthorized('Unauthorized to modify this resource');
        }
    }
}
