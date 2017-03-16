<?php

namespace App\Transformers\V1;

use App\Models\Patient;
use League\Fractal\TransformerAbstract;

class PatientTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'users',
    ];
    
    /**
     * @param Patient $patient
     * @return array
     */
    public function transform(Patient $patient)
    {
        return [
            'id' => $patient->id,
            'user_id' => $patient->user_id,
            'birthdate' => $patient->birthdate,
            'height_feet' => $patient->height_feet,
            'height_inches' => $patient->height_inches,
            'links' => [
                [
                    'rel' => 'patient.user',
                    'uri' => 'api/v1/users/' . $patient->user_id
                ]
            ]
        ];
    }
    
    /**
     * @param Patient $patient
     * @return mixed
     */
    public function includeUsers(Patient $patient)
    {
        $user = $patient->user;
        return $this->item($user, new UserTransformer());
    }
}
