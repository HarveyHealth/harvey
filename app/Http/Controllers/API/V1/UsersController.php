<?php

namespace App\Http\Controllers\API\V1;

use App\Events\OutOfServiceZipCodeRegistered;
use App\Events\UserRegistered;
use App\Models\Patient;
use App\Models\User;
use App\Transformers\V1\UserTransformer;
use Illuminate\Http\Request;
use ResponseCode;
use Validator;
use App\Lib\PhoneNumberVerifier;

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
            $indexed = filter_var(request('indexed'), FILTER_VALIDATE_BOOLEAN);

            if ($term && !$indexed) {
                $query = User::matching($term);
            } elseif ($term) {
                $query = User::search($term);
            } else {
                $query = User::make();
            }

            if (in_array($type, ['patient', 'practitioner', 'admin'])) {
                $typePlural = str_plural($type);
                // Scout\Builder (indexed search) doesn't support query scopes :( such as $query->practitioners().
                $query = $indexed ? $query->where('type', $type) : $query->$typePlural();
            }

            return $this->baseTransformBuilder($query, request('include'), new UserTransformer, request('per_page'))->respond();
        }

        return $this->respondNotAuthorized('You are not authorized to access this resource.');
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
            if($validator->errors()->get('zip')) {
                event(new OutOfServiceZipCodeRegistered($request));
            }
            return $this->respondBadRequest($validator->errors()->first());
        }

        try {
            $user = new User(
                $request->only(['first_name', 'last_name', 'email', 'zip'])
            );

            $user->password = bcrypt($request->password);
            $user->save();
            event(new UserRegistered($user));
            $user->patient()->save(new Patient());

            return $this->baseTransformItem($user)->respond(ResponseCode::HTTP_CREATED);
        } catch (\Exception $exception) {
            return $this->respondBadRequest($exception->getMessage());
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
            return $this->respondNotAuthorized("You do not have access to view the user with id {$user->id}.");
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
            return $this->respondBadRequest($validator->errors()->first());
        }

        if (auth()->user()->can('update', $user)) {
            $user->update($request->all());

            return $this->baseTransformItem($user)->respond();
        } else {
            return $this->respondNotAuthorized("You do not have access to modify the user with id {$user->id}.");
        }
    }

    public function verifyPhone(Request $request, User $user)
    {
        $code = $request->get('code');

        $verifier = new PhoneNumberVerifier($user);

        $verfied = false;

        if ($verifier->isValid($code)) {
            $verified = true;
        }

        return response(['verified' => $verified]);
    }

    public function sendVerificationCode(Request $request, User $user)
    {
        $verifier = new PhoneNumberVerifier($user);
        $verifier->sendVerificationCode();

        return response(['status' => 'Verification code sent.']);
    }
}
