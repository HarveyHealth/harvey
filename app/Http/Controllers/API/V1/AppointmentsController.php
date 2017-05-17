<?php

namespace App\Http\Controllers\API\V1;

use App\Events\AppointmentScheduled;
use App\Models\Appointment;
use App\Models\Patient;
use App\Transformers\V1\AppointmentTransformer;
use Illuminate\Http\Request;
use Carbon;
use ResponseCode;
use Validator;

class AppointmentsController extends BaseAPIController
{
    protected $resource_name = 'appointments';

    /**
     * AppointmentsController constructor.
     * @param AppointmentTransformer $transformer
     */
    public function __construct(AppointmentTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (currentUser()->isAdmin()) {
            $appointments = Appointment::orderBy('appointment_at', 'asc');
        } else {
            $appointments = currentUser()->appointments();
        }

        if (in_array($filter = request('filter'), ['recent', 'upcoming'])) {
            $appointments = $appointments->$filter();
        }

        return $this->baseTransformCollection($appointments->get(), request()->get('include')) ->respond();

    }

    /**
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Appointment $appointment)
    {
        if (currentUser()->can('view', $appointment)) {
            return $this->baseTransformItem(
                    $appointment,
                    request('include'))
                    ->respond();
        } else {
            return $this->respondNotAuthorized("You do not have access to view the appointment with id {$appointment->id}.");
        }
    }

    /**
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $inputData = $request->all();
        $validator = Validator::make($inputData, [
            'appointment_at' => 'required|date_format:Y-m-d H:i:s|after:' . Carbon::now(),
            'reason_for_visit' => 'required',
            'practitioner_id' => 'required_if_is_admin|required_if_is_patient|exists:practitioners,id',
            'patient_id' => 'required_if_is_admin|required_if_is_practitioner|exists:patients,id',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest($validator->errors()->first());
        }

        if (currentUser()->isPatient()) {
            $inputData['patient_id'] = currentUser()->id;
        } elseif (currentUser()->isPractitioner()) {
            $inputData['practitioner_id'] = currentUser()->id;
        }

        $appointment = Appointment::create($inputData);

        event(new AppointmentScheduled($appointment));

        return $this->baseTransformItem($appointment->fresh())->respond();
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (currentUser()->can('update', $appointment)) {
            $appointment->update($request->all());

            return $this->baseTransformItem($appointment)->respond();
        } else {
            $message = $appointment->isLocked() ?
                "You are unable to modify an appointment with less than "
                    . Appointment::CANCEL_LOCK . " hours of notice."
                : "You do not have access to update this appointment.";

            return $this->respondNotAuthorized($message);
        }
    }

    public function delete(Appointment $appointment)
    {
        if (currentUser()->can('delete', $appointment)) {
            $appointment->delete();

            return $this->baseTransformItem($appointment)
                        ->addMeta(['deleted' => true])
                        ->respond(ResponseCode::HTTP_NO_CONTENT);
        } else {
            $message = $appointment->isLocked() ?
                "You are unable to cancel an appointment with less than "
                    . Appointment::CANCEL_LOCK . " hours of notice."
                : "You do not have access to cancel this appointment.";

            return $this->respondNotAuthorized($message);
        }
    }
}
