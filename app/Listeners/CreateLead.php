<?php

namespace App\Listeners;

use App\Events\OutOfServiceZipCodeRegistered;
use App\Models\Lead;

class CreateLead
{
    public function handle(OutOfServiceZipCodeRegistered $event)
    {
        try {
            $lead = Lead::firstOrCreate($event->request->only('email'));
            $lead->update(
                array_merge(
                    $event->request->only(['first_name', 'last_name', 'zip']),
                    ['notes' => $event->notes ?? null]
                )
            );
        } catch (\Exception $exception) {
            \Log::error(
                'Unable to create lead with request: ',
                [$event->request, $exception->getMessage()]
            );
        }
    }
}
