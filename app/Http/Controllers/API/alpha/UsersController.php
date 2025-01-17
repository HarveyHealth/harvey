<?php

namespace App\Http\Controllers\API\alpha;

use App\Http\Controllers\API\alpha\Transformers\UserTransformer;
use App\Http\Controllers\API\alpha\Transformers\AppointmentTransformer;
use App\Models\User;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;

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
     * @apiSuccess {String} data.id
     * @apiSuccess {String} data.first_name First name of the user.
     * @apiSuccess {String} data.last_name Last name of the user.
     * @apiSuccess {String} data.user_type Type of user ['admin', 'patient', 'practitioner']
     * @apiSuccess {String} data.phone
     * @apiSuccess {Boolean} data.payment_info Indicates if billing information has been entered for the user
     * @apiSuccess {String} data.api_token Used to make authentication/authorization calls to the API
     * @apiSuccess {Object} meta Wrapper for meta content
     * @apiSuccessExample {json} Success-Response:
     * {
     *      "data": {
     *          "id": 5,
     *          "first_name": "Cedrick",
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
     * @apiParam {String} stripe_token
     *
     *
     * @apiSuccess {Object} data Wrapper for data content
     * @apiSuccess {String} data.id
     * @apiSuccess {String} data.first_name First name of the user.
     * @apiSuccess {String} data.last_name Last name of the user.
     * @apiSuccess {String} data.user_type Type of user ['admin', 'patient', 'practitioner']
     * @apiSuccess {String} data.phone
     * @apiSuccess {Boolean} data.payment_info Indicates if billing information has been entered for the user
     * @apiSuccess {String} data.api_token Used to make authentication/authorization calls to the API
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
        // Remove null and empty values from the request
        $filtered_array = array_filter($request->all());

        $validator = \Validator::make($filtered_array, [
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'email',
            'phone' => 'max:10',
            'gender' => 'string',
            'birthdate' => 'date',
            'height_feet' => 'numeric|between:1,10',
            'height_inches' => 'numeric|between:1,11',
            'weight' => 'integer',
            'symptoms' => 'json'
        ]);

        if ($validator->fails()) {
            return $this->respondUnprocessable($validator->messages());
        }


        if (auth()->user()->can('update', $user)) {
            \Log::info($request->all());

            // if there's a stripe_token
            // add th
            $token = $request->input('stripe_token');
            if (!empty($token)) {
                if (!empty($user->stripe_customer_id)) {
                    // TODO - update the stripe account with the new pay source
                } else {
                    // create the stripe customer
                    $customer = \Stripe\Customer::create(array(
                      'email' => $user->email,
                      'source' => $token,
                      'description' => $user->full_name . ' [' . $user->id . ']',
                      'metadata' => [
                          'harvey_user_id' => $user->id,
                          ]
                    ));

                    $filtered_array['stripe_customer_id'] = $customer->id;
                    $filtered_array['stripe_expiry_month'] = $customer->sources->data[0]->exp_month;
                    $filtered_array['stripe_expiry_year'] = $customer->sources->data[0]->exp_year;
                    $filtered_array['stripe_last_four'] = $customer->sources->data[0]->last4;
                }
            }

            // remove the token from the data
            unset($filtered_array['stripe_token']);

            $user->update($filtered_array);
            $transformedUser = $this->transformer->transform($user);

            return $this->respond($transformedUser, ['updated' => true]);
        } else {
            return $this->respondNotAuthorized('Unauthorized to modify this resource');
        }
    }
}
