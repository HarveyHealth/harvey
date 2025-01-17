<?php

namespace App\Http\Controllers\API\alpha;

use \Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Http\Controllers\API\alpha\Transformers\AppointmentTransformer;

class AppointmentsController extends BaseAPIController
{
    protected $users;
    protected $transformer;
    protected $appointments;
    
    public function __construct(AppointmentTransformer $transformer,
                                UserRepository $users,
                                AppointmentRepository $appointments)
    {
        $this->users = $users;
        $this->transformer = $transformer;
        $this->appointments = $appointments;
    }
    
    /**
     * @api {get} /appointments View appointments
     * @apiName GetAppointments
     * @apiGroup Appointment
     *
     * @apiParam {String} filter Options: ['upcoming', 'recent']
     *
     * @apiSuccess {Object[]} data Array of appointment objects
     * @apiSuccess {Number} data.id appointment id
     * @apiSuccess {Datetime} data.appointment_at appointment start datetime
     * @apiSuccess {String} data.reason_for_visit
     * @apiSuccess {Datetime} data.created_at Wrapper for data content
     * @apiSuccess {Object} meta Wrapper for data content
     * @apiSuccess {String} meta.filter Feedback of the filter used
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *      "data": [
     *          {
     *              "id": 24,
     *              "appointment_at": {
     *                  "date": "2017-02-02 15:00:00.000000",
     *                  "timezone_type": 3,
     *                  "timezone": "UTC"
     *               },
     *               "reason_for_visit": "This is the reason for the appointment",
     *               "created_at": {
     *                  "date": "2017-01-24 19:37:24.000000",
     *                  "timezone_type": 3,
     *                  "timezone": "UTC"
     *                }
     *            },
     *            {
     *              "id": 25,
     *              "appointment_at": {
     *                  "date": "2017-02-03 15:00:00.000000",
     *                  "timezone_type": 3,
     *                  "timezone": "UTC"
     *               },
     *               "reason_for_visit": "This is the reason for the appointment",
     *               "created_at": {
     *                  "date": "2017-01-25 19:37:24.000000",
     *                  "timezone_type": 3,
     *                  "timezone": "UTC"
     *                }
     *            }
     *          ],
     *     "meta": {
     *         "filter": "upcoming"
     *     }
     *}
     * */
    public function index()
    {
        if (request('filter') && request('filter') == 'upcoming') {
            $query = $this->appointments->upcoming();
        } elseif (request('filter') && request('filter') == 'recent') {
            $query = $this->appointments->recent();
        } else {
            $query = $this->appointments;
        }
        
        $user = $this->users->getByApiToken(request('api_token'));
        
        if ($user->isPatient()) {
            $appointment_list = $query->forPatient($user->id)->get();
        } elseif ($user->isPractitioner()) {
            $appointment_list = $query->forPractitioner($user->id)->get();
        } else {
            $appointment_list = $query->all();
        }
        
        $transformed_appointments = $this->transformer
                                        ->transformCollection($appointment_list);
        
        return $this->respond($transformed_appointments, ['filtered' => request('filter') ?: 'none']);
    }
    
    /**
     * @api {post} /appointments Create a new appointment
     * @apiName CreateAppointment
     * @apiGroup Appointment
     *
     * @apiParam {Date} selectedDate
     * @apiParam {Number} selectedTime
     * @apiParam {String} details
     *
     * @apiSuccess {Object} data Wrapper for data content
     * @apiSuccess {Number} data.id appointment id
     * @apiSuccess {Datetime} data.appointment_at appointment start datetime
     * @apiSuccess {String} data.reason_for_visit
     * @apiSuccess {Datetime} data.created_at Wrapper for data content
     * @apiSuccess {Object} meta Wrapper for data content
     * @apiSuccessExample {json} Success-Response:
     * {
     *      "data": {
     *         "id": 24,
     *         "appointment_at": {
     *             "date": "2017-02-02 15:00:00.000000",
     *             "timezone_type": 3,
     *             "timezone": "UTC"
     *          },
     *          "reason_for_visit": "This is the reason for the appointment",
     *          "created_at": {
     *              "date": "2017-01-24 19:37:24.000000",
     *              "timezone_type": 3,
     *              "timezone": "UTC"
     *          }
     *      },
     *     "meta": {
     *         "status": "created"
     *     }
     *}
     * */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'selectedDate' => 'required',
            'selectedTime' => 'required',
            'details' => 'required'
        ]);
        
        if ($validator->fails()) {
            return $this->respondBadRequest($validator);
        }
        
        $date_selected = $request->selectedDate;
        $carbon_date = Carbon::parse($date_selected);
        $carbon_date->hour = $request->selectedTime;
        
        $appointment = Appointment::create([
            'appointment_at' => $carbon_date,
            'reason_for_visit' => $request->details,
            'patient_user_id' => auth()->user()->id,
            'practitioner_user_id' => User::whereUserType('practitioner')->first()->id
        ]);
        
        
        $transformedAppointment = $this->transformer->transform($appointment);
        return $this->respond($transformedAppointment, ['status' => 'created']);
    }
    
    /**
     * @api {get} /appointments/:id View a specific appointment
     * @apiName CreateAppointment
     * @apiGroup Appointment
     *
     * @apiParam {Number} Appointment id
     *
     * */
    public function show(Appointment $appointment)
    {
        $transformedAppointment = $this->transformer->transform($appointment);
        return $this->respond($transformedAppointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    
    /**
     * @param Appointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Appointment $appointment)
    {
        $transformedAppointment = $this->transformer->transform($appointment);
        $appointment->delete(); // soft delete
        return $this->respond($transformedAppointment, ['deleted' => true]);
    }
}
