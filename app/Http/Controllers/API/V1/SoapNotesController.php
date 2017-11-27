<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{Patient, SoapNote};
use App\Transformers\V1\{SoapNotePatientTransformer, SoapNoteAdminOrPractitionerTransformer};
use Illuminate\Http\Request;
use Exception, ResponseCode, Storage;

class SoapNotesController extends BaseAPIController
{
    protected $resource_name = 'soap_note';
    protected $patient_transformer, $admin_or_practitioner_transformer;
    /**
     * SoapNotesController constructor.
     * @param SoapNotePatientTransformer $patient_transformer
     * @param SoapNoteAdminOrPractitionerTransformer $admin_or_practitioner_transformer
     */
    public function __construct(SoapNotePatientTransformer $patient_transformer, SoapNoteAdminOrPractitionerTransformer $admin_or_practitioner_transformer)
    {
        parent::__construct();
        $this->patient_transformer = $patient_transformer;
        $this->admin_or_practitioner_transformer = $admin_or_practitioner_transformer;
    }

    public function getOne(Request $request, SoapNote $soapNote)
    {
        if (currentUser()->cant('get', $soapNote)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this SOAP Note.');
        }

        $builder = $patient->soapNotes()->where('id', $soapNote->id);

        if (!currentUser()->isAdminOrPractitioner()) {
            $builder = $builder->filterForPatient();
        }

        return $this->baseTransformItem($builder->first())->respond();
    }

    public function store(Request $request, Patient $patient)
    {
        if (currentUser()->cant('storeSoapNote', $patient)) {
            return $this->respondNotAuthorized('You do not have access to store SoapNotes for this Patient.');
        }

        StrictValidator::check($request->all(), [
            'subjective' => 'string|max:2048',
            'objective' => 'string|max:2048',
            'assessment' => 'string|max:2048',
            'plan' => 'string|max:2048',
        ]);

        $soapNote = new SoapNote($request->all());
        $soapNote->created_by_user_id = currentUser()->id;

        $patient->soapNotes()->save($soapNote);

        return $this->baseTransformItem($soapNote->fresh())->respond();
    }

    public function update(Request $request, SoapNote $soapNote)
    {
        if (currentUser()->cant('update', $soapNote)) {
            return $this->respondNotAuthorized('You do not have access to update this SoapNote.');
        }

        StrictValidator::checkUpdate($request->all(), [
            'subjective' => 'filled|string|max:2048',
            'objective' => 'filled|string|max:2048',
            'assessment' => 'filled|string|max:2048',
            'plan' => 'filled|string|max:2048',
        ]);

        $soapNote->update($request->all());

        return $this->baseTransformItem($soapNote->fresh())->respond();
    }

    public function delete(Request $request, SoapNote $soapNote)
    {
        if (currentUser()->cant('delete', $soapNote)) {
            return $this->respondNotAuthorized('You do not have access to delete this SoapNote.');
        }

        if (!$soapNote->delete()) {
            return $this->baseTransformItem($soapNote)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }

    public function baseTransformItem($item, $include = null, $transformer = null, $resourceName = null)
    {
        if (empty($transformer)) {
            if (currentUser()->isPatient()) {
                $this->transformer = $this->patient_transformer;
            } elseif (currentUser()->isAdminOrPractitioner()) {
                $this->transformer = $this->admin_or_practitioner_transformer;
            }
        }

        return parent::baseTransformItem($item, $include, $transformer, $resourceName);
    }

}
