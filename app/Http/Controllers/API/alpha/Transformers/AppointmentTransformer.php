<?php

namespace App\Http\Controllers\API\alpha\Transformers;

class AppointmentTransformer extends Transformer
{
    public function transform($appointment)
    {
        return [
            'id' => $appointment->id,
            'appointment_at' => $appointment->appointment_at,
            'reason_for_visit' => $appointment->reason_for_visit,
            'created_at' => $appointment->created_at
      ];
    }
}
