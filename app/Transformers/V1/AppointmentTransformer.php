<?php

namespace App\Transformers\V1;

use App\Models\Appointment;
use League\Fractal\TransformerAbstract;

class AppointmentTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['patient', 'practitioner', 'discount_code'];

    /**
     * @param Appointment $appointment
     * @return array
     */
    public function transform(Appointment $appointment)
    {
        return [
            'id' => (string) $appointment->id,
            'appointment_at' => $appointment->appointment_at,
            'discount_code_id' => $appointment->discount_code_id,
            'duration_in_minutes' => is_null($appointment->duration_in_minutes) ? null : (string) $appointment->duration_in_minutes,
            'google_meet_link' => $appointment->google_meet_link,
            'patient_id' => (string) $appointment->patient_id,
            'practitioner_id' => (string) $appointment->practitioner_id,
            'practitioner_name' => (string) $appointment->practitioner->user->full_name,
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

    /**
     * @param Appointment $appointment
     * @return mixed
     */
    public function includeDiscountCode(Appointment $appointment)
    {
        if ($discount_code = $appointment->discountCode) {
            return $this->item($discount_code, new DiscountCodeTransformer(), 'discount_codes');
        }
    }

}
