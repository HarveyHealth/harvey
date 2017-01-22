<?php

namespace App\Http\Controllers\API\alpha;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\AppointmentRepository;
use App\Http\Controllers\API\alpha\Transformers\UserTransformer;
use App\Http\Controllers\API\alpha\Transformers\AppointmentTransformer;

class UsersController extends BaseAPIController
{
    protected $transformer;
    protected $appointmentTransformer;
    protected $appointments;
    
    public function __construct(UserTransformer $transformer,
                                AppointmentTransformer $appointmentTransformer,
                                AppointmentRepository $appointments)
    {
        $this->transformer = $transformer;
        $this->appointmentTransformer = $appointmentTransformer;
        $this->appointments = $appointments;
    }
    
    public function index(Request $request)
    {
        // List multiple users
    }

    public function store(Request $request)
    {
        // I don't foresee the need to create multiple users at a time.
    }

    public function create(Request $request)
    {
        // This logic is in the RegisterController
    }
    
    /**
     * @api {get} /users/:id Request User information
     * @apiName GetUser
     * @apiGroup User
     *
     * @apiParam {Number} id Users unique ID.
     *
     * @apiSuccess {Object} data Wrapper for data content
     * @apiSuccess {String} id
     * @apiSuccess {String} data.first_name First name of the user.
     * @apiSuccess {String} data.last_name Last name of the user.
     * @apiSuccess {String} user_type Type of user ['admin', 'patient', 'practitioner']
     * @apiSuccess {String} phone
     * @apiSuccess {Boolean} payment_info Indicates if billing information has been entered for the user
     * @apiSuccess {String} api_token Used to make authentication/authorization calls to the API
     * @apiSuccess {Object} meta Wrapper for meta content
     * @apiSuccessExample {json} Success-Response:
     * {
     *      "data": {
     *          "id": 5,
     *          "first_name": "Sedrick",
     *          "last_name": "Crooks",
     *          "user_type": "practitioner",
     *          "phone": "6261492166",
     *          "payment_info": true,
     *          "api_token": "SiG3uw7ppsdw9GxX8aiIOl1cLAE4WL1iqQSjYQLfIboq92T4QVtdWsTmceQL"
     *      },
     *      "meta": null
     *      }
     */
    public function show(User $user)
    {
        if (auth()->user()->can('view', $user)) {
            $transformedUser = $this->transformer->transform($user);
            return $this->respond($transformedUser);
        } else {
            return $this->respondNotAuthorized('Unauthorized to view this resource');
        }
    }
    
    /**
     * @api {patch} /users/:id Update User information
     * @apiName UpdateUser
     * @apiGroup User
     *
     * @apiParam {Number} id Users unique ID.
     * @apiParam {String} first_name
     * @apiParam {String} last_name
     * @apiParam {String} image_url
     * @apiParam {String} email
     * @apiParam {String} phone
     * @apiParam {String} address_1
     * @apiParam {String} address_2
     * @apiParam {String} city
     * @apiParam {String} state
     * @apiParam {String} zip
     * @apiParam {String} latitude
     * @apiParam {String} longitude
     * @apiParam {String} timezone
     * @apiParam {Date} birthdate
     * @apiParam {String} gender
     * @apiParam {Integer} height_feet
     * @apiParam {Integer} height_inches
     * @apiParam {Integer} weight
     * @apiParam {String} stripe_customer_id
     *
     *
     * @apiSuccess {Object} data Wrapper for data content
     * @apiSuccess {String} id
     * @apiSuccess {String} data.first_name First name of the user.
     * @apiSuccess {String} data.last_name Last name of the user.
     * @apiSuccess {String} user_type Type of user ['admin', 'patient', 'practitioner']
     * @apiSuccess {String} phone
     * @apiSuccess {Boolean} payment_info Indicates if billing information has been entered for the user
     * @apiSuccess {String} api_token Used to make authentication/authorization calls to the API
     * @apiSuccess {Object} meta Wrapper for meta content
     * @apiSuccessExample {json} Success-Response:
     * {
     *      "data": {
     *          "id": 5,
     *          "first_name": "Sedrick",
     *          "last_name": "Crooks",
     *          "user_type": "practitioner",
     *          "phone": "6261492166",
     *          "payment_info": true,
     *          "api_token": "SiG3uw7ppsdw9GxX8aiIOl1cLAE4WL1iqQSjYQLfIboq92T4QVtdWsTmceQL"
     *      },
     *      "meta": null
     *      }
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->can('update', $user)) {
            $user->update($request->except('api_token'));
            $transformedUser = $this->transformer->transform($user);
            return $this->respond($transformedUser, ['updated' => true]);
        } else {
            return $this->respondNotAuthorized('Unauthorized to modify this resource');
        }
    }
    
    public function appointments(User $user)
    {
        if (auth()->user()->cant('view', $user)) {
            return $this->respondNotAuthorized('Unauthorized to view this resource');
        }
        
        if ($user->user_type == 'patient') {
            $appointments = $this->appointments->forPatient($user->id)->get();
        } elseif ($user->user_type == 'practitioner') {
            $appointments = $this->appointments->forPractitioner($user->id)->get();
        } else {
            $appointments = $this->appointments->limit(10)->get();
        }
    
        $transformedAppointments = $this->appointmentTransformer
            ->transformCollection($appointments);
        
        return $this->respond($transformedAppointments);
    }
}
