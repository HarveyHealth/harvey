<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Patient;
use App\Transformers\V1\PatientTransformer;
use App\Lib\Validation\StrictValidator;
use Illuminate\Http\Request;

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
            $term = request('term');
            $builder = Patient::make()->with('user');

            if ($term) {
                $builder = $builder->whereIn('id', Patient::search($term)->get()->pluck('id'));
            }

            return $this->baseTransformBuilder($builder, request('include'), $this->transformer, request('per_page'))->respond();
        }

        return $this->respondNotAuthorized('You are not authorized to access this patient.');
    }

    /**
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Patient $patient)
    {
        if (auth()->user()->can('view', $patient)) {
            return $this->baseTransformItem($patient, request('include'))->respond();
        }

        return $this->respondNotAuthorized("You do not have access to view the patient with id {$patient->id}.");
    }

    /**
     * @param Request $request
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Patient $patient)
    {
        if (auth()->user()->can('update', $patient)) {
            StrictValidator::check($request->all(), [
                'birthdate' => 'date',
                'height_inches' => 'integer',
                'height_feet' => 'integer',
                'weight' => 'integer'
            ]);

            $patient->update($request->all());
            return $this->baseTransformItem($patient)->respond();
        }

        return $this->respondNotAuthorized('You do not have access to modify this patient.');
    }
}
