<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Patient;
use App\Transformers\V1\PatientTransformer;
use Crell\ApiProblem\ApiProblem;
use Illuminate\Http\Request;
use \Validator;

class PatientsController extends BaseAPIController
{
    protected $resource_name = 'patients';

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (auth()->user()->isAdminOrPractitioner()) {
            return $this->baseTransformBuilder(Patient::make(), null, $this->transformer, request('per_page'))->respond();
        }

        $problem = new ApiProblem();
        $problem->setDetail('You are not authorized to access this resource.');

        return $this->respondNotAuthorized($problem);
    }

    /**
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Patient $patient)
    {
        if (auth()->user()->can('view', $patient)) {
            return $this->baseTransformItem($patient)->respond();
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
        $validator = Validator::make($request->all(), [
            'birthdate' => 'date',
            'height_inches' => 'integer',
            'height_feet' => 'integer',
            'weight' => 'integer'
        ]);

        if ($validator->fails()) {
            $problem = new ApiProblem();
            $problem->setDetail($validator->errors()->first());
            return $this->respondBadRequest($problem);
        }

        if (auth()->user()->can('update', $patient)) {
            $patient->update($request->all());

            return $this->baseTransformItem($patient)->respond();
        } else {
            $problem = new ApiProblem();
            $problem->setDetail('You do not have access to modify this patient.');
            return $this->respondNotAuthorized($problem);
        }
    }
}
