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
            'created_by_user_id' => cast_to_string($attachment->creator->id),
            'name' => $attachment->name,
            'notes' => $attachment->notes,
            'url' => $attachment->url,
        ];
    }
}
