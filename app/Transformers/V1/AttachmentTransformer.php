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
            'id' => (string) $attachment->id,
            'created_by_user_id' => (string) $attachment->creator->id,
            'notes' => $attachment->notes,
            'url' => $attachment->url,
        ];
    }
}
