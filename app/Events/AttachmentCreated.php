<?php

namespace App\Events;

use App\Models\Attachment;
use Illuminate\Foundation\Events\Dispatchable;

class AttachmentCreated
{
    use Dispatchable;

    public $attachment;

    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }
}
