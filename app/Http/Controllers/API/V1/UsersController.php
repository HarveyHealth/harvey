<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Transformers\V1\UserTransformer;
use Crell\ApiProblem\ApiProblem;
use Illuminate\Http\Request;

class UsersController extends BaseAPIController
{
    protected $resource_name = 'users';

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
            return $this->transformedResponse($user);
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to view the user with id {$user->id}.");
            return $this->respondNotAuthorized($problem);
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

            return $this->transformedResponse($user);
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to modify the user with id {$user->id}.");
            return $this->respondNotAuthorized($problem);
        }
    }
}
