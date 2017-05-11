<?php

namespace App\Http\Controllers\API\V1;

use App\Events\UserRegistered;
use App\Models\Patient;
use App\Models\User;
use App\Transformers\V1\UserTransformer;
use Crell\ApiProblem\ApiProblem;
use Illuminate\Http\Request;
use Validator;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $term = request('term');
            $type = request('type');
            $indexed = request('indexed');

            if ($term && !$indexed) {
                $query = User::matching($term)->withoutGlobalScopes();
            } elseif ($term) {
                $query = User::search($term);
            } else {
                $query = User::withoutGlobalScopes();
            }

            if (in_array($type, ['patient', 'practitioner'])) {
                $typePlural = str_plural($type);
                // Scout\Builder (indexed search) doesn't support query scopes :( such as $query->practitioners().
                $query = $indexed ? $query->where('user_type', $type) : $query->$typePlural();
            }

            return $this->baseTransformBuilderPaginated($query, new UserTransformer, request('per_page'))->respond();
        }

        $problem = new ApiProblem();
        $problem->setDetail('You are not authorized to access this resource.');

        return $this->respondNotAuthorized($problem);
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
