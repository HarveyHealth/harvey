<?php

namespace App\Http\Controllers\API\V1;

use App\Events\AppointmentScheduled;
use App\Models\Appointment;
use App\Transformers\V1\AppointmentTransformer;
use Crell\ApiProblem\ApiProblem;
use Illuminate\Http\Request;
use \ResponseCode;
use \Validator;

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

        if(request('filter') == 'recent') {
            $appointments = $appointments->recent();
        }

        if(request('filter') == 'upcoming') {
            $appointments = $appointments->upcoming();
        }

        return $this->baseTransformCollection($appointments->get())->respond();
    }

    /**
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Appointment $appointment)
    {
        if (auth()->user()->can('view', $appointment)) {
            return $this->baseTransformItem($appointment)->respond();
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to view the appointment with id {$appointment->id}.");
            return $this->respondNotAuthorized($problem);
        }
    }

    /**
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_at' => 'required',
            'reason_for_visit' => 'required',
            'practitioner_id' => 'required'
        ]);

        if ($validator->fails()) {
            $problem = new ApiProblem();
            $problem->setDetail($validator->errors()->first());
            return $this->respondBadRequest($problem);
        }

        $appointment = new Appointment($request->all());

        if (auth()->user()->can('create', $appointment)) {
            $patient = auth()->user()->patient;
            $patient->appointments()->save($appointment);

            event(new AppointmentScheduled($appointment));

            return $this->baseTransformItem($appointment)->respond();
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to schedule a new appointment.");
            return $this->respondNotAuthorized($problem);
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

            $problem = new ApiProblem();
            $problem->setDetail($message);
            return $this->respondNotAuthorized($problem);
        }
    }

    public function delete(Appointment $appointment)
    {
        if (auth()->user()->can('delete', $appointment) && $appointment->isNotLocked()) {
            $appointment->delete();

            return $this->baseTransformItem($appointment)
                        ->addMeta(['deleted' => true])
                        ->respond(ResponseCode::HTTP_GONE);
        } else {
            $message = $appointment->isLocked() ?
                "You are unable to cancel an appointment with less than "
                    . Appointment::CANCEL_LOCK . " hours of notice."
                : "You do not have access to cancel this appointment.";

            $problem = new ApiProblem();
            $problem->setDetail($message);
            return $this->respondNotAuthorized($problem);
        }
    }
}
