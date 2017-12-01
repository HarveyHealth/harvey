<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{Patient, SoapNote};
use App\Transformers\V1\SoapNoteTransformer;
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
    public function __construct(SoapNoteTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    public function getOne(Request $request, SoapNote $soap_note)
    {
        if (currentUser()->cant('get', $soap_note)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this SOAP Note.');
        }

        return $this->baseTransformItem($soap_note)->respond();
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

        $soap_note = new SoapNote($request->all());
        $soap_note->created_by_user_id = currentUser()->id;

        $patient->soapNotes()->save($soap_note);

        return $this->baseTransformItem($soap_note->fresh())->respond();
    }

    public function update(Request $request, SoapNote $soap_note)
    {
        if (currentUser()->cant('update', $soap_note)) {
            return $this->respondNotAuthorized('You do not have access to update this SoapNote.');
        }

        StrictValidator::checkUpdate($request->all(), [
            'subjective' => 'filled|string|max:2048',
            'objective' => 'filled|string|max:2048',
            'assessment' => 'filled|string|max:2048',
            'plan' => 'filled|string|max:2048',
        ]);

        $soap_note->update($request->all());

        return $this->baseTransformItem($soap_note->fresh())->respond();
    }

    public function delete(Request $request, SoapNote $soap_note)
    {
        if (currentUser()->cant('delete', $soap_note)) {
            return $this->respondNotAuthorized('You do not have access to delete this SoapNote.');
        }

        if (!$soap_note->delete()) {
            return $this->baseTransformItem($soap_note)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
