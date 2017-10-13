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

        return $this->baseTransformBuilder($appointments, request('include'))->respond();
    }

    /**
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Appointment $appointment)
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
        $validator = StrictValidator::check($inputData, [
            'appointment_at' => 'required|date_format:Y-m-d H:i:s|after:now|before:4 weeks|practitioner_is_available',
            'cancellation_reason' => 'max:1024',
            'duration_in_minutes' => 'integer',
            'patient_id' => 'required_if_is_admin|required_if_is_practitioner|exists:patients,id',
            'practitioner_id' => 'required_if_is_admin|required_if_is_patient|exists:practitioners,id',
            'reason_for_visit' => 'required',
            'status' => ['filled', Rule::in(Appointment::STATUSES)],
        ]);

        if (currentUser()->isPatient()) {
            $inputData['patient_id'] = currentUser()->patient->id;
        } elseif (currentUser()->isPractitioner()) {
            $inputData['practitioner_id'] = currentUser()->practitioner->id;
        }

        if ($request->has('discount_code')) {
          if ($inputData['discount_code'] !== null) {
            $discount_code = DiscountCode::findByValidCodeApplicationAndUser($inputData['discount_code'], 'consultation', currentUser());
            if ($discount_code) {
                $inputData['discount_code_id'] = $discount_code->id;
            }
            unset($inputData['discount_code']);
          }
        }

        $appointment = Appointment::create($inputData);

        return $this->baseTransformItem($appointment->fresh())->respond();
    }

    public function update(Request $request, Appointment $appointment)
    {
        if (currentUser()->can('update', $appointment)) {
            StrictValidator::checkUpdate($request->all(), [
                'appointment_at' => "date_format:Y-m-d H:i:s|after:now|before:4 weeks|practitioner_is_available:{$appointment->id}",
                'cancellation_reason' => 'filled',
                'duration_in_minutes' => 'integer',
                'reason_for_visit' => 'filled',
                'status' => ['filled', Rule::in(Appointment::STATUSES)],
            ]);

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
