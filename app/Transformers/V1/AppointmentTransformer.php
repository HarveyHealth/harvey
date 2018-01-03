<?php

namespace App\Transformers\V1;

use App\Models\Appointment;
use League\Fractal\TransformerAbstract;

class AppointmentTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['patient', 'practitioner', 'discount_code', 'invoice'];

    /**
     * @param Appointment $appointment
     * @return array
     */
    public function transform(Appointment $appointment)
    {
        return [
            'id' => cast_to_string($appointment->id),
            'appointment_at' => $appointment->appointment_at,
            'discount_code_id' => cast_to_string($appointment->discount_code_id),
            'duration_in_minutes' => cast_to_string($appointment->duration_in_minutes),
            'google_meet_link' => $appointment->google_meet_link,
            'patient_id' => cast_to_string($appointment->patient_id),
            'practitioner_id' => cast_to_string($appointment->practitioner_id),
            'practitioner_name' => cast_to_string($appointment->practitioner->user->full_name),
            'reason_for_visit' => cast_to_string($appointment->reason_for_visit),
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
        )->setResourceKey('patient');
    }

    /**
     * @param Appointment $appointment
     * @return mixed
     */
    public function includeInvoice(Appointment $appointment)
    {
        if ($invoice = $appointment->invoice){
            return $this->item($invoice, new InvoiceTransformer())->setResourceKey('invoice');
        }
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
        )->setResourceKey('practitioner');
    }

    /**
     * @param Appointment $appointment
     * @return mixed
     */
    public function includeDiscountCode(Appointment $appointment)
    {
        if ($discount_code = $appointment->discountCode) {
            return $this->item($discount_code, new DiscountCodeTransformer(), 'discount_code');
        }
    }

}
