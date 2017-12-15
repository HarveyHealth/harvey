<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Practitioner;
use Illuminate\Http\Request;
use App\Lib\Validation\StrictValidator;
use App\Transformers\V1\PractitionerScheduleTransformer;
use App\Models\PractitionerSchedule;
use ResponseCode;
use App\Http\Requests\StorePractitionerSchedule;

class PractitionerScheduleController extends BaseAPIController
{
    protected $resource_name = 'practitioners_schedules';

    public function __construct(PractitionerScheduleTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    public function index()
    {
        if (currentUser()->isNotAdminOrPractitioner()) {
            return $this->respondNotAuthorized('You are not authorized to access this practitioner schedule.');
        }

        return $this->baseTransformCollection(currentUser()->practitioner->schedule)->respond();
    }

    public function show(Practitioner $practitioner, PractitionerSchedule $practitionerSchedule, Request $request)
    {
        if (currentUser()->can('view', $practitionerSchedule)) {
            return $this->respondNotAuthorized('You do not have access to view this practitioner schedule.');
        }

        return $this->baseTransformItem($practitionerSchedule)->respond();
    }

    public function store(Practitioner $practitioner, StorePractitionerSchedule $request)
    {
        if (currentUser()->isNotPractitioner()) {
            return $this->respondNotAuthorized('You do not have access to create a practitioner schedule.');
        }

        $practitionerSchedule = PractitionerSchedule::create([
            'practitioner_id' => $practitioner->id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'stop_time' => $request->stop_time,
            'notes' => $request->notes,
        ]);

        return $this->baseTransformItem($practitionerSchedule)->respond(ResponseCode::HTTP_CREATED);
    }

    public function update(Practitioner $practitioner, PractitionerSchedule $practitionerSchedule, StorePractitionerSchedule $request)
    {
        if (currentUser()->can('edit', $practitionerSchedule)) {
            return $this->respondNotAuthorized('You do not have access to modify this practitioner schedule.');
        }

        $practitionerSchedule->update($request->except('practitioner_id'));
        return $this->baseTransformItem($practitionerSchedule);
    }

    public function destroy(Practitioner $practitioner, PractitionerSchedule $practitionerSchedule)
    {
        if (currentUser()->can('delete', $practitionerSchedule)) {
            return $this->respondNotAuthorized('You do not have access to delete this practitioner schedule.');
        }

        if (!$practitionerSchedule->delete()) {
            return $this->baseTransformItem($practitionerSchedule)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
