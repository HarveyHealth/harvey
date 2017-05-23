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
     * @param Appointment $appointment
     * @return array
     */
    public function transform(Appointment $appointment)
    {
        return [
            'appointment_at' => $appointment->appointment_at,
            'id' => (string) $appointment->id,
            'patient_id' => (string) $appointment->patient_id,
            'practitioner_id' => (string) $appointment->practitioner_id,
            'practitioner_name' => (string) $appointment->practitioner->user->fullName(),
            'reason_for_visit' => (string) $appointment->reason_for_visit,
            'status' => $appointment->status,
        ];
    }

    /**
     * @param Appointment $appointment
     * @return mixed
     */
    public function includePatient(Appointment $appointment)
    {
        $patient = $appointment->patient;
        return $this->item(
            $patient,
            new PatientTransformer()
        )->setResourceKey('patients');
    }

    /**
     * @param Appointment $appointment
     * @return mixed
     */
    public function includePractitioner(Appointment $appointment)
    {
        $practitioner = $appointment->practitioner;
        return $this->item(
            $practitioner,
            new PractitionerTransformer()
        )->setResourceKey('practitioners');
    }
}
