<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{Attachment, Patient, Prescription, SoapNote};
use App\Transformers\V1\{AttachmentTransformer, PatientTransformer, PrescriptionTransformer, SoapNoteTransformer};
use App\Lib\Validation\StrictValidator;
use Illuminate\Http\Request;
use Storage;

class PatientsController extends BaseAPIController
{
    protected $resource_name = 'patient';

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
    public function getAll()
    {
        if (currentUser()->isAdminOrPractitioner()) {
            return $this->baseTransformBuilder(Patient::make()->with('user'), request('include'), $this->transformer, request('per_page'))->respond();
        }

        return $this->respondNotAuthorized('You are not authorized to access this patient.');
    }

    /**
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(Patient $patient)
    {
        if (currentUser()->cant('view', $patient)) {
            return $this->respondNotAuthorized("You do not have access to view the patient with id {$patient->id}.");
        }

        return $this->baseTransformItem($patient, request('include'))->respond();
    }

    /**
     * @param Request $request
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Patient $patient)
    {
        if (currentUser()->cant('update', $patient)) {
            return $this->respondNotAuthorized('You do not have access to modify this patient.');
        }

        StrictValidator::check($request->all(), [
            'birthdate' => 'date',
            'height_inches' => 'integer',
            'height_feet' => 'integer',
            'weight' => 'integer'
        ]);

        $patient->update($request->all());

        return $this->baseTransformItem($patient)->respond();
    }
}
