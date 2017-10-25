<?php

namespace App\Transformers\V1;

use App\Models\Patient;
use League\Fractal\TransformerAbstract;

class PatientTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'appointments'];

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
        ];
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeUser(Patient $patient)
    {
        $user = $patient->user;
        return $this->item($user, new UserTransformer())->setResourceKey('users');
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeAppointments(Patient $patient)
    {
        $appointments = $patient->appointments;
        return $this->collection($appointments, new AppointmentTransformer())->setResourceKey('appointments');
    }
}
