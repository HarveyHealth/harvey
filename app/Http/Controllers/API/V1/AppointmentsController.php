<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{Appointment, Patient};
use App\Transformers\V1\AppointmentTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon;
use ResponseCode;
use App\Models\DiscountCode;

class AppointmentsController extends BaseAPIController
{
    protected $resource_name = 'appointment';

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
    public function getAll()
    {
        if (currentUser()->isAdmin()) {
            $builder = Appointment::orderBy('appointment_at', 'asc');
        } else {
            $builder = currentUser()->appointments();
        }

        if (in_array($filter = request('filter'), ['recent', 'upcoming'])) {
            $builder = $builder->$filter();
        }

        $builder = $builder->with('patient.user')->with('practitioner.user');

        return $this->baseTransformBuilder($builder, request('include'))->respond();
    }

    /**
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(Appointment $appointment)
    {
        if (currentUser()->can('view', $appointment)) {
            return $this->baseTransformItem($appointment, request('include'))->respond();
        }

        return $this->respondNotAuthorized("You do not have access to view the appointment with id {$appointment->id}.");
    }

    /**
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $inputData = $request->all();

        if (currentUser()->isPatient()) {
            $inputData['patient_id'] = currentUser()->patient->id;
        } elseif (currentUser()->isPractitioner()) {
            $inputData['practitioner_id'] = currentUser()->practitioner->id;
        }

        StrictValidator::check($inputData, [
            'appointment_at' => 'required|date_format:Y-m-d H:i:s|after:now|before:4 weeks|practitioner_is_available',
            'cancellation_reason' => 'max:1024',
            'duration_in_minutes' => 'integer',
            'notes' => 'filled|admin_or_practitioner',
            'patient_id' => 'required|exists:patients,id|appointments_less_than:2',
            'practitioner_id' => 'required|exists:practitioners,id',
            'reason_for_visit' => 'required',
            'status' => ['filled', Rule::in(Appointment::STATUSES)],
        ], [
            'appointments_less_than' => "Sorry, you can't have more than two appointments at a time."
        ]);

        $appointment = Appointment::create($inputData);
        $appointment->setDiscountCode(currentUser(), $request->input('discount_code'), 'consultation')->save();

        return $this->baseTransformItem($appointment->fresh())->respond();
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (currentUser()->cant('update', $appointment)) {
            if ($appointment->isLocked() || $appointment->isNotPending()) {
                $message = "This Appointment is locked for updates.";
            } else {
                $message = "You do not have access to update this appointment.";
            }
            return $this->respondNotAuthorized($message);
        }

        StrictValidator::checkUpdate($request->all(), [
            'appointment_at' => "date_format:Y-m-d H:i:s|after:now|before:4 weeks|practitioner_is_available:{$appointment->id}",
            'cancellation_reason' => 'filled',
            'duration_in_minutes' => 'integer',
            'notes' => 'filled|admin_or_practitioner',
            'reason_for_visit' => 'filled',
            'status' => ['filled', Rule::in(Appointment::STATUSES)],
        ]);

        $appointment->update($request->all());

        return $this->baseTransformItem($appointment)->respond();
    }

    public function delete(Appointment $appointment)
    {
        if (currentUser()->cant('delete', $appointment)) {
            if ($appointment->isLocked() || $appointment->isNotPending()) {
                $message = "This Appointment is locked for canceling.";
            } else {
                $message = "You do not have access to cancel this appointment.";
            }
            return $this->respondNotAuthorized($message);
        }

        if (!$appointment->delete()) {
            return $this->baseTransformItem($appointment)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
