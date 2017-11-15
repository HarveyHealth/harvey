<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\Prescription;
use App\Transformers\V1\PrescriptionTransformer;
use Illuminate\Http\Request;
use Exception, ResponseCode, Storage;

class PrescriptionsController extends BaseAPIController
{
    protected $resource_name = 'prescription';

    /**
     * PrescriptionsController constructor.
     * @param PrescriptionTransformer $transformer
     */
    public function __construct(PrescriptionTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    public function getOne(Request $request, Prescription $prescription)
    {
        if (currentUser()->cant('get', $prescription)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this Prescription.');
        }

        return $this->baseTransformItem($prescription)->respond();
    }

    public function store(Request $request, Patient $patient)
    {
        if (currentUser()->cant('storePrescription', $patient)) {
            return $this->respondNotAuthorized('You do not have access to store Prescriptions for this Patient.');
        }

        $validator = StrictValidator::check($request->all(), [
            'file' => 'required|mimes:bmp,img,jpeg,pdf,png',
            'notes' => 'string|max:1024',
        ]);

        $relative_path = (string) $patient->user->id;

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path,
                $request->file('file'),
                $fileName = "Prescription_{$patient->prescriptions->withoutGlobalScopes()->count()}.pdf",
                [
                    'visibility' => 'private',
                    'ContentType' => $request->file('file')->getMimeType(),
                ]
            );

            $prescription = new Prescription($request->only('notes'));
            $prescription->created_by_user_id = currentUser()->id;
            $prescription->key = "{$relative_path}/{$fileName}";

            $patient->prescriptions()->save($prescription);
        } catch (Exception $e) {
            return $this->respondUnprocessable($e->getMessage());
        }

        return $this->baseTransformItem($prescription->fresh())->respond();
    }

    public function deletePrescription(Request $request, Prescription $prescription)
    {
        if (currentUser()->cant('delete', $prescription)) {
            return $this->respondNotAuthorized('You do not have access to delete this Prescription.');
        }

        if (!$prescription->delete()) {
            return $this->baseTransformItem($prescription)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
