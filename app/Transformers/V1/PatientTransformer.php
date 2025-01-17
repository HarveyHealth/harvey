<?php

namespace App\Transformers\V1;

use App\Lib\Fractal\HarveyTransformer;
use App\Models\Patient;

class PatientTransformer extends HarveyTransformer
{
    protected $availableIncludes = [
        'appointments',
        'attachments',
        'intake',
        'lab_orders',
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
            'id' => cast_to_string($patient->id),
            'birthdate' => $patient->birthdate,
            'height_feet' => cast_to_string($patient->height_feet),
            'height_inches' => cast_to_string($patient->height_inches),
            'intake_token' => $patient->intake_token,
            'name' => $patient->user->full_name,
            'user_id' => cast_to_string($patient->user_id),
            'weight' => cast_to_string($patient->weight),
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
        return $this->collection($patient->appointments, new AppointmentTransformer())->setResourceKey('appointment');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeAttachments(Patient $patient)
    {
        return $this->collection($patient->attachments, new AttachmentTransformer())->setResourceKey('attachment');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeIntake(Patient $patient)
    {
        if (empty($intake = $patient->intake)) {
            return $this->null();
        }

        return $this->item($intake, new IntakeTransformer())->setResourceKey('intake');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includePrescriptions(Patient $patient)
    {
        return $this->collection($patient->prescriptions, new PrescriptionTransformer())->setResourceKey('prescription');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeSoapNotes(Patient $patient)
    {
        return $this->collection($patient->soapNotes, new SoapNoteTransformer())->setResourceKey('soap_note');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeLabOrders(Patient $patient)
    {
        return $this->collection($patient->labOrders, new LabOrderTransformer())->setResourceKey('lab_order');
    }
}
