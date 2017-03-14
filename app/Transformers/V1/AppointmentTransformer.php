<?php

namespace App\Transformers\V1;

use App\Models\Appointment;
use League\Fractal\TransformerAbstract;

class AppointmentTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'patient', 'practitioner'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Appointment $appointment)
    {
        return [
            'id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'practitioner_id' => $appointment->practitioner_id,
            'appointment_at' => $appointment->appointment_at,
            'reason_for_visit' => $appointment->reason_for_visit
        ];
    }
    
    public function includePatient(Appointment $appointment)
    {
        $patient = $appointment->patient;
        return $this->item($patient, new PatientTransformer());
    }
    
    public function includePractitioner(Appointment $appointment)
    {
        $practitioner = $appointment->practitioner;
        return $this->item($practitioner, new PractitionerTransformer());
    }
}
