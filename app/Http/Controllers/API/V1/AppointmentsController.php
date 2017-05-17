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
        if (auth()->user()->isAdmin()) {
            $appointments = Appointment::orderBy('appointment_at', 'asc');
        } else {
            $appointments = auth()->user()->appointments();
        }

        if (in_array($filter = request('filter'), ['recent', 'upcoming'])) {
            $appointments = $appointments->$filter();
        }

        return $this->baseTransformCollection($appointments->get(), request('include')) ->respond();
    }

    /**
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Appointment $appointment)
    {
        if (auth()->user()->can('view', $appointment)) {
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
        $validator = Validator::make($request->all(), [
            'appointment_at' => 'required|date_format:Y-m-d H:i:s|after:' . Carbon::now(),
            'reason_for_visit' => 'required',
            'practitioner_id' => 'required|exists:practitioners,id',
            'patient_id' => 'required_if_is_admin|exists:patients,id',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest($validator->errors()->first());
        }

        $appointment = new Appointment($request->all());

        if (auth()->user()->can('create', $appointment)) {
            if (auth()->user()->isAdmin()) {
                $patient = Patient::find($request->get('patient_id'));
            } else {
                $patient = auth()->user()->patient;
            }

            $patient->appointments()->save($appointment);

            event(new AppointmentScheduled($appointment));

            return $this->baseTransformItem($appointment->fresh())->respond();
        } else {
            return $this->respondNotAuthorized("You do not have access to schedule a new appointment.");
        }
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (auth()->user()->can('update', $appointment)) {
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
        if (auth()->user()->can('delete', $appointment)) {
            $appointment->delete();

            return $this->baseTransformItem($appointment)
                        ->addMeta(['deleted' => true])
                        ->respond(ResponseCode::HTTP_GONE);
        } else {
            $message = $appointment->isLocked() ?
                "You are unable to cancel an appointment with less than "
                    . Appointment::CANCEL_LOCK . " hours of notice."
                : "You do not have access to cancel this appointment.";

            return $this->respondNotAuthorized($message);
        }
    }
}
