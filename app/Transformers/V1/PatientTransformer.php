<?php

namespace App\Transformers\V1;

use App\Models\Patient;
use League\Fractal\TransformerAbstract;

class PatientTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'appointments',
        'attachments',
        'intake',
        'prescriptions',
        'soap_notes',
        'user',
    ];

    /**
     * @param Patient $patient
     * @return array
     */
    public function transform(Patient $patient)
    {
        return [
            'id' => (string) $patient->id,
            'birthdate' => $patient->birthdate,
            'height_feet' => (int) $patient->height_feet,
            'height_inches' => (int) $patient->height_inches,
            'name' => $patient->user->full_name,
            'user_id' => (string) $patient->user_id,
            'weight' => (string) $patient->weight,
        ];
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeUser(Patient $patient)
    {
        return $this->item($patient->user, new UserTransformer())->setResourceKey('user');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeAppointments(Patient $patient)
    {
        return $this->collection($patient->appointments, new AppointmentTransformer())->setResourceKey('appointments');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeAttachments(Patient $patient)
    {
        return $this->collection($patient->attachments, new AttachmentTransformer())->setResourceKey('attachments');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeIntake(Patient $patient)
    {
        if (empty($patient->getIntakeData())) {
            return $this->null();
        }

        return $this->item($patient, new IntakeTransformer())->setResourceKey('intake');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includePrescriptions(Patient $patient)
    {
        return $this->collection($patient->prescriptions, new PrescriptionTransformer())->setResourceKey('prescriptions');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeSoapNotes(Patient $patient)
    {
        return $this->collection($patient->soapNotes, new SoapNoteTransformer())->setResourceKey('soap_notes');
    }
}
