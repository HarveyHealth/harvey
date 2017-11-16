<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{Attachment, Patient};
use App\Transformers\V1\AttachmentTransformer;
use Illuminate\Http\Request;
use Exception, ResponseCode, Storage;

class AttachmentsController extends BaseAPIController
{
    protected $resource_name = 'attachment';

    /**
     * AttachmentsController constructor.
     * @param AttachmentTransformer $transformer
     */
    public function __construct(AttachmentTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    public function getOne(Request $request, Attachment $attachment)
    {
        if (currentUser()->cant('get', $attachment)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this attachment.');
        }

        return $this->baseTransformItem($attachment)->respond();
    }

    public function store(Request $request, Patient $patient)
    {
        if (currentUser()->cant('storeAttachment', $patient)) {
            return $this->respondNotAuthorized('You do not have access to store Attachments for this Patient.');
        }

        $validator = StrictValidator::check($request->all(), [
            'file' => 'required|mimes:bmp,img,jpeg,pdf,png',
            'name' => 'string|max:64',
            'notes' => 'string|max:1024',
        ]);

        $relative_path = (string) $patient->user->id;

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path,
                $request->file('file'),
                $fileName = "Attachment_{$patient->attachments()->withoutGlobalScopes()->count()}.pdf",
                [
                    'visibility' => 'private',
                    'ContentType' => $request->file('file')->getMimeType(),
                ]
            );

            $attachment = new Attachment($request->only(['name', 'notes']));
            $attachment->created_by_user_id = currentUser()->id;
            $attachment->key = "{$relative_path}/{$fileName}";

            $patient->attachments()->save($attachment);
        } catch (Exception $e) {
            return $this->respondUnprocessable($e->getMessage());
        }

        return $this->baseTransformItem($attachment->fresh())->respond();
    }

    public function delete(Request $request, Attachment $attachment)
    {
        if (currentUser()->cant('delete', $attachment)) {
            return $this->respondNotAuthorized('You do not have access to delete this Attachment.');
        }

        if (!$attachment->delete()) {
            return $this->baseTransformItem($attachment)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
