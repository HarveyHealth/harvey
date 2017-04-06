<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;

class PractitionerSchedule extends BaseAPIController
{
    protected $resource_name = 'practitioner_schedules';

    /**
     * @param Practitioner $practitioner
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Practitioner $practitioner)
    {
        if (auth()->user()->can('view', $practitioner)) {
            $availabilities = $practitioner->availability;
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to view the practitioner with id {$practitioner->id}.");
            return $this->respondNotAuthorized($problem);
        }
    }

    /**
     * @param Request $request
     * @param Practitioner $practitioner
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Practitioner $practitioner)
    {
        $validator = Validator::make($request->all(), [
            'birthdate' => 'date',
            'height_inches' => 'integer',
            'height_feet' => 'integer',
            'weight' => 'integer'
        ]);

        if ($validator->fails()) {
            $problem = new ApiProblem();
            $problem->setDetail($validator->errors()->first());
            return $this->respondBadRequest($problem);
        }

        if (auth()->user()->can('update', $practitioner)) {
        } else {
            $problem = new ApiProblem();
            $problem->setDetail('You do not have access to modify this patient.');
            return $this->respondNotAuthorized($problem);
        }
    }
}
