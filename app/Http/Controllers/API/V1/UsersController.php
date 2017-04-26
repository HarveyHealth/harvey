<?php

namespace App\Http\Controllers\API\V1;

use App\Events\UserRegistered;
use App\Models\Patient;
use App\Models\User;
use App\Transformers\V1\UserTransformer;
use Crell\ApiProblem\ApiProblem;
use Illuminate\Http\Request;
use \Validator;

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
    
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'max:100',
            'last_name' => 'max:100',
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|min:6',
            'terms' => 'required|accepted',
            'zip' => 'required|digits:5|serviceable'
        ], [
            'serviceable' => 'Sorry, we do not service this :attribute.'
        ]);
    
        if ($validator->fails()) {
            $problem = new ApiProblem();
            $problem->setDetail($validator->errors()->first());
            return $this->respondBadRequest($problem);
        }
        
        try {
            $user = new User(
                $request->only(['first_name', 'last_name', 'email', 'zip'])
            );
            
            $user->password = bcrypt($request->password);
            $user->save();
            event(new UserRegistered($user));
            $user->patient()->save(new Patient());
            
            return $this->baseTransformItem($user)->respond();
        } catch (\Exception $exception) {
            $problem = new ApiProblem();
            $problem->setDetail($exception->getMessage());
            return $this->respondBadRequest($problem);
        }
    }
    
    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        if (auth()->user()->can('view', $user)) {
            return $this->baseTransformItem($user)->respond();
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
        $validator = Validator::make($request->all(), [
            'first_name' => 'max:100',
            'last_name' => 'max:100',
            'email' => 'email|max:150|unique:users',
            'zip' => 'digits:5|serviceable',
            'phone' => 'unique:users'
        ], [
            'serviceable' => 'Sorry, we do not service this :attribute.'
        ]);
    
        if ($validator->fails()) {
            $problem = new ApiProblem();
            $problem->setDetail($validator->errors()->first());
            return $this->respondBadRequest($problem);
        }
        
        if (auth()->user()->can('update', $user)) {
            $user->update($request->all());
    
            return $this->baseTransformItem($user)->respond();
        } else {
            $problem = new ApiProblem();
            $problem->setDetail("You do not have access to modify the user with id {$user->id}.");
            return $this->respondNotAuthorized($problem);
        }
    }
}
