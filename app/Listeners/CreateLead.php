<?php

namespace App\Listeners;

use App\Events\OutOfServiceZipCodeRegistered;
use App\Models\Lead;

class CreateLead
{
    public function handle(OutOfServiceZipCodeRegistered $event)
    {
        try {
            $lead = new Lead($event->request->only(['first_name', 'last_name', 'email', 'zip']));
            $lead->notes = $event->notes ?? null;
            $lead->save();
        } catch (\Exception $exception) {
            \Log::error('Unable to create lead with request: ', [$event->request, $exception->getMessage()]);
        }
    }
}
