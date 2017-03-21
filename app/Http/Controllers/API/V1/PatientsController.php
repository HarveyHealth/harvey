<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Patient;
use App\Transformers\V1\PatientTransformer;
use Crell\ApiProblem\ApiProblem;
use Illuminate\Http\Request;

class PatientsController extends BaseAPIController
{
    /**
     * @var PatientTransformer
     */
    protected $transformer;
    
    /**
     * PatientsController constructor.
     * @param PatientTransformer $transformer
     */
    public function __construct(PatientTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }
    
    /**
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Patient $patient)
    {
        if (auth()->user()->can('view', $patient)) {
            return $response = fractal()->item($patient)
                ->withResourceName('patients')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
               ->parseIncludes(['users', 'appointments'])
                ->toArray();
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to view the patient with id {$patient->id}.");
            return $this->respondNotAuthorized($problem);
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
            $this->problem->setDetail('You do not have access to modify this patient.');
            return $this->respondNotAuthorized();
        }
    }
}
