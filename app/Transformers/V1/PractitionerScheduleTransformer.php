<?php

namespace App\Transformers\V1;

use League\Fractal\TransformerAbstract;
use App\Models\PractitionerSchedule;

class PractitionerScheduleTransformer extends TransformerAbstract
{
    public function transform(PractitionerSchedule $practitionerSchedule)
    {
        return [
            'id' => $practitionerSchedule->id,
            'practitioner_id' => $practitionerSchedule->practitioner_id,
            'day_of_week' => $practitionerSchedule->day_of_week,
            'start_time' => $practitionerSchedule->start_time,
            'stop_time' => $practitionerSchedule->stop_time,
            'notes' => $practitionerSchedule->notes,
        ];
    }
}
