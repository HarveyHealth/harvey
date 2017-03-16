<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Transformers\V1\UserTransformer;
use Illuminate\Http\Request;
use Stripe\Customer;

class UsersController extends BaseAPIController
{
    protected $transformer;
    
    public function __construct(UserTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    
    /**
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\JsonResponse
     */s
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
        ]);
    
        if ($validator->fails()) {
            return $this->respondUnprocessable($validator->messages());
        }
        
        if (auth()->user()->can('update', $user)) {
            $token = $request->input('stripe_token');
            if (!empty($token)) {
                if (!empty($user->stripe_customer_id)) {
                    // TODO - update the stripe account with the new pay source
                } else {
                    $customer = Customer::create(array(
                        'email' => $user->email,
                        'source' => $token,
                        'description' => "{$user->fullname()} [{$user->id}]",
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
            
            return fractal()->item($user)
                ->transformWith($this->transformer)
                ->respond();
        } else {
            return $this->respondNotAuthorized('Unauthorized to modify this resource');
        }
    }
}
