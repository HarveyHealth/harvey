<?php

namespace App\Http\Controllers\API\V1;

use App\Events\{OutOfServiceZipCodeRegistered, UserRegistered};
use App\Lib\PhoneNumberVerifier;
use App\Lib\Validation\StrictValidator;
use App\Models\{Patient, User};
use App\Transformers\V1\UserTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ResponseCode, Validator;

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
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized('You are not authorized to access this resource.');
        }

        $term = request('term');
        $type = request('type');
        $order = explode('|', request('order'));

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

        if (in_array($order[0], $query->getModel()->allowedSortBy)) {
            $query = $query->orderBy('created_at', $order[1] ?? null);
        }

        return $this->baseTransformBuilder($query, request('include'), new UserTransformer, request('per_page'))->respond();
    }

    public function create(Request $request)
    {
        $validator = StrictValidator::make($request->all(), [
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
        if (currentUser()->can('view', $user)) {
            return $this->baseTransformItem($user, request('include'))->respond();
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
        if (currentUser()->cant('update', $user)) {
            return $this->respondNotAuthorized("You do not have access to modify the user with id {$user->id}.");
        }

        StrictValidator::checkUpdate($request->all(), [
            'first_name' => 'max:100',
            'last_name' => 'max:100',
            'email' => 'email|max:150|unique:users',
            'zip' => 'digits:5|serviceable',
            'phone' => 'max:10|unique:users',
            'address_1' => 'max:100',
            'address_2' => 'max:100',
            'city' => 'max:100',
            'state' => 'max:2',
            'timezone' => 'max:75',
        ], [
            'serviceable' => 'Sorry, we do not service this :attribute.'
        ]);

        $user->update($request->all());

        return $this->baseTransformItem($user)->respond();
    }

    public function phoneVerify(Request $request, User $user)
    {
        StrictValidator::check($request->all(), [
            'code' => 'digits:5',
        ]);

        if ($verified = PhoneNumberVerifier::isValid($user, request('code'))) {
            $user->markPhoneAsVerified();
        }

        return response(compact('verified'));
    }

    public function sendVerificationCode(Request $request, User $user)
    {
        if (currentUser()->id != $user->id && currentUser()->isNotAdmin()) {
            return response()->json(['status' => 'Verification code not sent.'], ResponseCode::HTTP_FORBIDDEN);
        }

        $user->sendVerificationCode();

        return response()->json(['status' => 'Verification code sent.']);
    }
}
