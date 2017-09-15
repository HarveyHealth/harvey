<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{Attachment, Patient, Prescription, SoapNote};
use App\Transformers\V1\{AttachmentTransformer, PatientTransformer, PrescriptionTransformer, SoapNoteTransformer};
use App\Lib\Validation\StrictValidator;
use Illuminate\Http\Request;
use Storage;

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
    public function getAll()
    {
        if (currentUser()->isAdminOrPractitioner()) {
            return $this->baseTransformBuilder(Patient::make(), request('include'), $this->transformer, request('per_page'))->respond();
        }

        return $this->respondNotAuthorized('You are not authorized to access this patient.');
    }

    /**
     * @param Patient $patient
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Patient $patient)
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

    public function getAttachments(Request $request, Patient $patient)
    {
        if (currentUser()->cant('view', $patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve attachments of this Patient.');
        }
        $this->resource_name = 'attachments';
        return $this->baseTransformBuilder($patient->attachments(), request('include'), new AttachmentTransformer, request('per_page'))->respond();
    }

    public function getAttachment(Request $request, Patient $patient, Attachment $attachment)
    {
        if (currentUser()->cant('view', $patient) || $attachment->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this attachment.');
        }
        $this->resource_name = 'attachments';
        return $this->baseTransformItem($attachment, request('include'), new AttachmentTransformer, request('per_page'))->respond();
    }

    public function storeAttachment(Request $request, Patient $patient)
    {
        if (currentUser()->cant('handleAttachment', $patient)) {
            return $this->respondNotAuthorized('You do not have access to store Attachments for this Patient.');
        }

        $validator = StrictValidator::check($request->all(), [
            'file' => 'required|mimes:bmp,img,jpeg,pdf,png',
            'name' => 'string|max:64',
            'notes' => 'string|max:1024',
        ]);

        $relative_path = "{$patient->user->id}";

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path,
                $request->file('file'),
                $fileName = "Attachment_{$patient->attachments->withoutGlobalScopes()->count()}.pdf",
                [
                    'visibility' => 'private',
                    'ContentType' => $request->file('file')->getMimeType(),
                ]
            );

            $patient->attachments()->create([
                'created_by_user_id' => currentUser()->id,
                'key' => "{$relative_path}/{$fileName}",
                'name' => request('name'),
                'notes' => request('notes'),
            ]);
        } catch (Exception $e) {
            return $this->respondUnprocessable($e->getMessage());
        }
        $this->resource_name = 'attachments';
        return $this->baseTransformItem($patient->fresh(), 'attachments')->respond();
    }

    public function deleteAttachment(Request $request, Patient $patient, Attachment $attachment)
    {
        if (currentUser()->cant('handleAttachment', $patient) || $attachment->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to delete this Attachment.');
        }
        $this->resource_name = 'attachments';
        if (!$attachment->delete()) {
            return $this->baseTransformItem($attachment)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }

    public function getPrescriptions(Request $request, Patient $patient)
    {
        if (currentUser()->cant('view', $patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve Prescriptions of this Patient.');
        }
        $this->resource_name = 'prescriptions';
        return $this->baseTransformBuilder($patient->prescriptions(), request('include'), new PrescriptionTransformer, request('per_page'))->respond();
    }

    public function getPrescription(Request $request, Patient $patient, Prescription $prescription)
    {
        if (currentUser()->cant('view', $patient) || $prescription->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this Prescription.');
        }
        $this->resource_name = 'prescriptions';
        return $this->baseTransformItem($prescription, request('include'), new PrescriptionTransformer, request('per_page'))->respond();
    }

    public function storePrescription(Request $request, Patient $patient)
    {
        if (currentUser()->cant('handlePrescription', $patient)) {
            return $this->respondNotAuthorized('You do not have access to store Prescriptions for this Patient.');
        }

        $validator = StrictValidator::check($request->all(), [
            'file' => 'required|mimes:bmp,img,jpeg,pdf,png',
            'notes' => 'string|max:1024',
        ]);

        $relative_path = "{$patient->user->id}";

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path,
                $request->file('file'),
                $fileName = "Prescription_{$patient->prescriptions->withoutGlobalScopes()->count()}.pdf",
                [
                    'visibility' => 'private',
                    'ContentType' => $request->file('file')->getMimeType()
                ]
            );

            $patient->prescriptions()->create([
                'created_by_user_id' => currentUser()->id,
                'key' => "{$relative_path}/{$fileName}",
                'notes' => request('notes'),
            ]);
        } catch (Exception $e) {
            return $this->respondUnprocessable($e->getMessage());
        }
        $this->resource_name = 'prescriptions';
        return $this->baseTransformItem($patient->fresh(), 'prescriptions')->respond();
    }

    public function deletePrescription(Request $request, Patient $patient, Prescription $prescription)
    {
        if (currentUser()->cant('handlePrescription', $patient) || $prescription->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to delete this Prescription.');
        }
        $this->resource_name = 'prescriptions';
        if (!$prescription->delete()) {
            return $this->baseTransformItem($prescription)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }

    public function getSoapNotes(Request $request, Patient $patient)
    {
        if (currentUser()->cant('view', $patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve SOAP Notes of this Patient.');
        }

        $builder = $patient->soapNotes();

        if (!currentUser()->isAdminOrPractitioner()) {
            $builder = $builder->filterForPatient();
        }
        $this->resource_name = 'soap_notes';
        return $this->baseTransformBuilder($builder, request('include'), new SoapNoteTransformer, request('per_page'))->respond();
    }

    public function getSoapNote(Request $request, Patient $patient, SoapNote $soapNote)
    {
        if (currentUser()->cant('view', $patient) || $soapNote->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this SOAP Note.');
        }

        $builder = $patient->soapNotes()->where('id', $soapNote->id);

        if (!currentUser()->isAdminOrPractitioner()) {
            $builder = $builder->filterForPatient();
        }
        $this->resource_name = 'soap_notes';
        return $this->baseTransformItem($builder->first(), request('include'), new SoapNoteTransformer)->respond();
    }

    public function storeSoapNote(Request $request, Patient $patient)
    {
        if (currentUser()->cant('handleSoapNote', $patient)) {
            return $this->respondNotAuthorized('You do not have access to store SoapNotes for this Patient.');
        }

        $validator = StrictValidator::check($request->all(), [
            'subjective' => 'string|max:2048',
            'objective' => 'string|max:2048',
            'assessment' => 'string|max:2048',
            'plan' => 'string|max:2048',
        ]);

        $relative_path = "{$patient->user->id}";

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path,
                $request->file('file'),
                $fileName = "SoapNote_{$patient->SoapNotes->withoutGlobalScopes()->count()}.pdf",
                [
                    'visibility' => 'private',
                    'ContentType' => $request->file('file')->getMimeType()
                ]
            );

            $patient->soapNotes()->create([
                'created_by_user_id' => currentUser()->id,
                'key' => "{$relative_path}/{$fileName}",
                'notes' => request('notes'),
            ]);
        } catch (Exception $e) {
            return $this->respondUnprocessable($e->getMessage());
        }
        $this->resource_name = 'soap_notes';
        return $this->baseTransformItem($patient->fresh(), 'soap_notes')->respond();
    }

    public function deleteSoapNote(Request $request, Patient $patient, SoapNote $soapNote)
    {
        if (currentUser()->cant('handleSoapNote', $patient) || $soapNote->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to delete this SoapNote.');
        }
        $this->resource_name = 'soap_notes';
        if (!$soapNote->delete()) {
            return $this->baseTransformItem($soapNote)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
