<?php

namespace App\Transformers\V1;

use League\Fractal\TransformerAbstract;
use App\Models\PractitionerScheduleOverride;

class PractitionerScheduleOverrideTransformer extends TransformerAbstract
{
    public function transform(PractitionerScheduleOverride $practitionerScheduleOverride)
    {
        return [
            'id' => $practitionerScheduleOverride->id,
            'practitioner_id' => $practitionerScheduleOverride->practitioner_id,
            'date' => $practitionerScheduleOverride->date,
            'start_time' => $practitionerScheduleOverride->start_time,
            'stop_time' => $practitionerScheduleOverride->stop_time,
            'notes' => $practitionerScheduleOverride->notes,
        ];
    }
}
