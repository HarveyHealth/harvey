<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Transformers\V1\UserTransformer;
use Illuminate\Http\Request;

class UsersController extends BaseAPIController
{
    /**
     * @var UserTransformer
     */
    protected $transformer;
    
    /**
     * UsersController constructor.
     * @param UserTransformer $transformer
     */
    public function __construct(UserTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }
    
    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        if (auth()->user()->can('view', $user)) {
            return fractal()->item($user)
                ->withResourceName('users')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        } else {
            $this->problem->setDetail("You do not have access to view the user with id {$user->id}.");
            return $this->respondNotAuthorized();
        }
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
            $this->problem->setDetail("You do not have access to modify the user with id {$user->id}.");
            return $this->respondNotAuthorized();
        }
    }
}
