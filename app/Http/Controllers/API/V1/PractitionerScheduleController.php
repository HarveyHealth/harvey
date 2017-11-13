<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Lib\Validation\StrictValidator;

class PractitionerScheduleController extends BaseAPIController
{
    protected $resource_name = 'practitioners_schedules';

    /**
     * @param Practitioner $practitioner
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Practitioner $practitioner)
    {
        if (auth()->user()->can('view', $practitioner)) {
            $availabilities = $practitioner->availability;
        } else {
            return $this->respondNotAuthorized("You do not have access to view the practitioner with id {$practitioner->id}.");
        }
    }

    /**
     * @param Request $request
     * @param Practitioner $practitioner
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Practitioner $practitioner)
    {
        StrictValidator::check($request->all(), [
            'birthdate' => 'date',
            'height_inches' => 'integer',
            'height_feet' => 'integer',
            'weight' => 'integer'
        ]);

        if (auth()->user()->can('update', $practitioner)) {
        } else {
            return $this->respondNotAuthorized('You do not have access to modify this patient.');
        }
    }
}
