<?php

namespace App\Observers;

use App\Events\{AttachmentCreated};
use App\Models\Attachment;

class AttachmentObserver
{
    /**
     * Listen to the Attachment created event.
     *
     * @param  Attachment $attachment
     * @return void
     */
    public function created(Attachment $attachment)
    {
        event(new AttachmentCreated($attachment));
    }
}
