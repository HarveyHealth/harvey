<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{Attachment};
use App\Transformers\V1\{AttachmentTransformer};
use Illuminate\Http\Request;
use League\Fractal\Serializer\JsonApiSerializer;
use Exception, ResponseCode;

class AttachmentsController extends BaseAPIController
{
    protected $resource_name = 'attachments';

    /**
     * AttachmentsController constructor.
     * @param AttachmentTransformer $transformer
     */
    public function __construct(AttachmentTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }


    public function getAll(Request $request, Patient $patient)
    {
        if (currentUser()->cant('view', $patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve attachments of this Patient.');
        }
        $this->resource_name = 'attachments';
        return $this->baseTransformBuilder($patient->attachments(), request('include'), new AttachmentTransformer, request('per_page'))->respond();
    }

    public function getOne(Request $request, Patient $patient, Attachment $attachment)
    {
        if (currentUser()->cant('view', $patient) || $attachment->patient->isNot($patient)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this attachment.');
        }
        $this->resource_name = 'attachments';
        return $this->baseTransformItem($attachment, request('include'), new AttachmentTransformer, request('per_page'))->respond();
    }

    public function store(Request $request, Patient $patient)
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

    public function delete(Request $request, Patient $patient, Attachment $attachment)
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



}
