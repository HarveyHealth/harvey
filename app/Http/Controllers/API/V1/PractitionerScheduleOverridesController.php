<?php

namespace App\Http\Controllers\API\V1;

use App\Models\{Practitioner, PractitionerScheduleOverride};
use App\Transformers\V1\PractitionerScheduleOverrideTransformer;
use App\Http\Requests\StorePractitionerScheduleOverride;
use ResponseCode;
use Illuminate\Http\Request;

class PractitionerScheduleOverridesController extends BaseAPIController
{
    protected $resource_name = 'practitioners_schedule_overrides';

    public function __construct(PractitionerScheduleOverrideTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    public function index()
    {
        if (currentUser()->isNotAdminOrPractitioner()) {
            return $this->respondNotAuthorized('You are not authorized to access this practitioner schedule.');
        }

        return $this->baseTransformCollection(currentUser()->practitioner->scheduleOverrides)->respond();
    }

    public function show(Practitioner $practitioner, PractitionerScheduleOverride $practitionerScheduleOverride, Request $request)
    {
        if (currentUser()->cant('view', $practitionerScheduleOverride)) {
            return $this->respondNotAuthorized('You do not have access to view this practitioner schedule.');
        }

        return $this->baseTransformItem($practitionerScheduleOverride)->respond();
    }

    public function store(Practitioner $practitioner, StorePractitionerScheduleOverride $request)
    {
        if (currentUser()->isNotPractitioner()) {
            return $this->respondNotAuthorized('You do not have access to create a practitioner schedule override.');
        }

        if($practitioner->scheduleOverrides->count() == 21) {
            return $this->respondNotAuthorized('You are limited to 21 schedule override entries');
        }

        $practitionerScheduleOverride = PractitionerScheduleOverride::create([
            'practitioner_id' => $practitioner->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'stop_time' => $request->stop_time,
            'notes' => $request->notes,
        ]);

        return $this->baseTransformItem($practitionerScheduleOverride)->respond(ResponseCode::HTTP_CREATED);
    }

    public function update(Practitioner $practitioner, PractitionerScheduleOverride $practitionerScheduleOverride, StorePractitionerScheduleOverride $request)
    {
        if (currentUser()->cant('edit', $practitionerScheduleOverride)) {
            return $this->respondNotAuthorized('You do not have access to modify this practitioner schedule override.');
        }

        $practitionerScheduleOverride->update($request->except('practitioner_id'));
        return $this->baseTransformItem($practitionerScheduleOverride);
    }

    public function destroy(Practitioner $practitioner, PractitionerScheduleOverride $practitionerScheduleOverride)
    {
        if (currentUser()->cant('delete', $practitionerScheduleOverride)) {
            return $this->respondNotAuthorized('You do not have access to delete this practitioner schedule override.');
        }

        if (!$practitionerScheduleOverride->delete()) {
            return $this->baseTransformItem($practitionerScheduleOverride)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
