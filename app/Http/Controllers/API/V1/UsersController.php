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
        parent::__construct();
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
    
    
    public function show(User $user)
    {
        if (auth()->user()->can('view', $user)){
            return fractal()->item($user)
                ->withResourceName('users')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        } else {
            return $this->respondNotAuthorized('Unauthorized to view this resource');
        }
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
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->can('update', $user)) {
            $user->update($request->all());
            
            return fractal()->item($user)
                ->withResourceName('users')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        } else {
            return $this->respondNotAuthorized('Unauthorized to modify this resource');
        }
    }
}
