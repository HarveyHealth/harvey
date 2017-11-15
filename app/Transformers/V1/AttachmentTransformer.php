<?php

namespace App\Transformers\V1;

use App\Models\Attachment;
use League\Fractal\TransformerAbstract;

class AttachmentTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Attachment $attachment)
    {
        return [
            'id' => cast_to_string($attachment->id),
            'patient_id' => cast_to_string($attachment->patient_id),
            'created_at' => $attachment->created_at,
            'created_by_user_id' => cast_to_string($attachment->creator->id),
            'doctor_name' => $attachment->doctor_name,
            'name' => $attachment->name,
            'notes' => $attachment->notes,
            'url' => $attachment->url,
        ];
    }
}
