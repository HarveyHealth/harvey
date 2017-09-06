<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{Attachment, Patient};
use App\Transformers\V1\{PatientTransformer, PrescriptionTransformer, AttachmentTransformer};
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

        return $this->baseTransformCollection($patient->attachments, request('include'), new AttachmentTransformer, request('per_page'))->respond();
    }

    public function getAttachment(Request $request, Patient $patient, Attachment $attachment)
    {
        if (currentUser()->cant('view', $patient) || $attachment->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this attachment.');
        }

        return $this->baseTransformItem($attachment, request('include'), new AttachmentTransformer, request('per_page'))->respond();
    }

    public function storeAttachment(Request $request, Patient $patient)
    {
        if (currentUser()->cant('handleAttachment', $patient)) {
            return $this->respondNotAuthorized('You do not have access to store Attachments for this Patient.');
        }

        $validator = StrictValidator::check($request->all(), [
            'file' => 'required|mimes:pdf',
            'notes' => 'string|max:1024',
        ]);

        $relative_path = "{$patient->user->id}";

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path,
                $request->file('file'),
                $fileName = "Attachment_{$patient->attachments->withoutGlobalScopes()->count()}.pdf",
                ['ContentType' => $request->file('file')->getMimeType()]
            );

            $patient->attachments()->save([
                'created_by_user_id' => currentUser()->id,
                'key' => "{$relative_path}/{$fileName}",
                'notes' => request('notes'),
            ]);

            return $this->baseTransformItem($patient->fresh(), 'attachments')->respond();
        } catch (Exception $e) {
            return $this->respondUnprocessable($e->getMessage());
        }
    }

    public function deleteAttachment(Request $request, Patient $patient, Attachment $attachment)
    {
        if (currentUser()->cant('handleAttachment', $patient) || $attachment->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to delete this Attachment.');
        }

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

        return $this->baseTransformCollection($patient->prescriptions, request('include'), new PrescriptionTransformer, request('per_page'))->respond();
    }

    public function getPrescription(Request $request, Patient $patient, Prescription $prescription)
    {
        if (currentUser()->cant('view', $patient) || $prescription->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this Prescription.');
        }

        return $this->baseTransformItem($prescription, request('include'), new PrescriptionTransformer, request('per_page'))->respond();
    }

    public function storePrescription(Request $request, Patient $patient)
    {
        if (currentUser()->cant('handlePrescription', $patient)) {
            return $this->respondNotAuthorized('You do not have access to store Prescriptions for this Patient.');
        }

        $validator = StrictValidator::check($request->all(), [
            'file' => 'required|mimes:pdf',
            'notes' => 'string|max:1024',
        ]);

        $relative_path = "{$patient->user->id}";

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path,
                $request->file('file'),
                $fileName = "Prescription_{$patient->prescriptions->withoutGlobalScopes()->count()}.pdf",
                ['ContentType' => $request->file('file')->getMimeType()]
            );

            $patient->prescriptions()->save([
                'created_by_user_id' => currentUser()->id,
                'key' => "{$relative_path}/{$fileName}",
                'notes' => request('notes'),
            ]);

            return $this->baseTransformItem($patient->fresh(), 'prescriptions')->respond();
        } catch (Exception $e) {
            return $this->respondUnprocessable($e->getMessage());
        }
    }

    public function deletePrescription(Request $request, Patient $patient, Prescription $prescription)
    {
        if (currentUser()->cant('handlePrescription', $patient) || $prescription->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to delete this Prescription.');
        }

        if (!$prescription->delete()) {
            return $this->baseTransformItem($prescription)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
