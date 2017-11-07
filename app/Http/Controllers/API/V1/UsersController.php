<?php

namespace App\Http\Controllers\API\V1;

use App\Events\{OutOfServiceZipCodeRegistered, UserRegistered};
use App\Lib\{PhoneNumberVerifier, Validation\StrictValidator, ZipCodeValidator};
use App\Models\{Patient, User};
use App\Transformers\V1\{CreditCardTransformer, UserTransformer};
use Crell\ApiProblem\ApiProblem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception, ResponseCode;

class UsersController extends BaseAPIController
{
    protected $resource_name = 'user';

    /**
     * UsersController constructor.
     * @param UserTransformer $transformer
     */
    public function __construct(UserTransformer $transformer, ZipCodeValidator $zipCodeValidator)
    {
        parent::__construct();
        $this->transformer = $transformer;
        $this->zipCodeValidator = $zipCodeValidator;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized('You are not authorized to access this resource.');
        }

        $term = request('term');
        $type = empty(array_intersect([request('type')], ['patient', 'practitioner', 'admin'])) ? null : request('type');
        $order = explode('|', request('order'));

        $indexed = filter_var(request('indexed'), FILTER_VALIDATE_BOOLEAN);

        if ($indexed) {
            $query = empty($term) ? User::make() : User::search($term);
            $model = $query->model;
        } else {
            $query = empty($term) ? User::make() : User::matching($term);
            $model = $query->getModel();
        }

        if ($type) {
            $typePlural = str_plural($type);
            // Scout\Builder (indexed search) doesn't support query scopes :( such as $query->practitioners().
            $query = $indexed ? $query->where('type', $type) : $query->$typePlural();
        }

        if (in_array($order[0], $model->allowedSortBy)) {
            $query = $query->orderBy('created_at', $order[1] ?? false);
        }

        return $this->baseTransformBuilder($query, request('include'), $this->transformer, request('per_page'))->respond();
    }

    public function store(Request $request)
    {
        $validator = StrictValidator::make($request->all(), [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|min:6',
            'terms' => 'required|accepted',
            'zip' => 'required|digits:5|serviceable'
        ], [
            'serviceable' => 'Sorry, we do not service this :attribute.'
        ]);

        $this->zipCodeValidator->setZip(request('zip'));
        $city = $this->zipCodeValidator->getCity();
        $state = $this->zipCodeValidator->getState();

        if ($validator->fails()) {
            $this->setApiProblemType($validator);

            // Error handling for zip code
            if ($validator->errors()->get('zip')) {
                event(new OutOfServiceZipCodeRegistered($request));

                $this->setStatusCode(ResponseCode::HTTP_BAD_REQUEST);
                $this->apiProblem->setTitle('Bad Request.');
                $output = $this->apiProblem->asArray();

                $output['detail'] = [
                    'message' => $validator->errors()->first(),
                    'city' => $city,
                    'state' => $state,
                ];

                return response()->apiproblem($output, $this->getStatusCode());
            }

            return $this->respondBadRequest(['message' => $validator->errors()->first()]);
        }

        try {
            $user = new User(
                $request->only(['first_name', 'last_name', 'email', 'zip']) + compact('city', 'state')
            );

            $user->password = bcrypt($request->password);
            $user->save();
            event(new UserRegistered($user));
            $user->patient()->save(new Patient());

            return $this->baseTransformItem($user)->respond(ResponseCode::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->respondBadRequest($exception->getMessage());
        }
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(User $user)
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
            'address_1' => 'max:100',
            'address_2' => 'max:100',
            'city' => 'max:100',
            'email' => 'email|max:150|unique:users',
            'gender' => 'max:255',
            'first_name' => 'max:100',
            'last_name' => 'max:100',
            'phone' => 'max:10|unique:users',
            'state' => 'max:2',
            'timezone' => 'max:75',
            'zip' => $user->isAdminOrPractitioner() ? 'digits:5' : 'digits:5|serviceable',
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
        if (currentUser()->isNot($user) && currentUser()->isNotAdmin()) {
            return response()->json(['status' => 'Verification code not sent.'], ResponseCode::HTTP_FORBIDDEN);
        }

        $user->sendVerificationCode();

        return response()->json(['status' => 'Verification code sent.']);
    }

    public function profileImageUpload(Request $request, User $user)
    {
        if (auth()->user()->cant('update', $user)) {
            return $this->respondNotAuthorized("You do not have access to modify the user with id {$user->id}.");
        }

        StrictValidator::check($request->only('image'), [
            'image' => 'required|dimensions:max_width=300,max_height=300',
        ]);

        try {
            $image = $request->file('image');
            $imagePath = 'profile-images/' . time() . $image->getFilename() . '.' . $image->getClientOriginalExtension();
            Storage::cloud()->put($imagePath, file_get_contents($image), 'public');
        } catch (Exception $exception) {
            return $this->respondWithError('Unable to upload profile image. Please try again later');
        }

        $user->update(['image_url' => Storage::cloud()->url($imagePath)]);

        return $this->baseTransformItem($user)->respond();
    }

    public function addCard(Request $request, User $user)
    {
        if (currentUser()->isNot($user) && currentUser()->isNotAdmin()) {
            return response()->json(['status' => false], ResponseCode::HTTP_FORBIDDEN);
        }

        StrictValidator::check($request->all(), [
            'id' => 'required|regex:/^tok_.*/',
        ]);

        if (!$card = $user->addCard(request('id'))) {
            return response()->json([], ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        $this->resource_name = "users/{$user->id}/card";

        return $this->baseTransformItem($card, null, new CreditCardTransformer)->respond(ResponseCode::HTTP_CREATED);
    }

    public function deleteCard(Request $request, User $user, string $cardId)
    {
        if (currentUser()->isNot($user) && currentUser()->isNotAdmin()) {
            return response()->json(['status' => false], ResponseCode::HTTP_FORBIDDEN);
        }

        StrictValidator::check(['card_id' => $cardId], [
            'card_id' => 'required|regex:/^card_.*/',
        ]);

        $responseCode = $user->deleteCard($cardId) ? ResponseCode::HTTP_NO_CONTENT : ResponseCode::HTTP_SERVICE_UNAVAILABLE;

        return response()->json([], $responseCode);
    }

    public function updateCard(Request $request, User $user, string $cardId)
    {
        if (currentUser()->isNot($user) && currentUser()->isNotAdmin()) {
            return response()->json(['status' => false], ResponseCode::HTTP_FORBIDDEN);
        }

        StrictValidator::check(['card_id' => $cardId], [
            'card_id' => 'required|regex:/^card_.*/',
        ]);

        StrictValidator::checkUpdate($request->all(), $validKeys = [
            'address_city' => 'sometimes',
            'address_country' => 'sometimes',
            'address_line1' => 'sometimes',
            'address_line2' => 'sometimes',
            'address_state' => 'sometimes',
            'address_zip' => 'sometimes',
            'exp_month' => 'sometimes|numeric|between:1,12',
            'exp_year' => 'sometimes|digits:4',
            'name' => 'sometimes',
        ]);

        if (!$card = $user->updateCard($cardId, $request->intersect(array_keys($validKeys)))) {
            return response()->json([], ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        $this->resource_name = "users/{$user->id}/card";

        return $this->baseTransformItem($card, null, new CreditCardTransformer)->respond(ResponseCode::HTTP_OK);
    }

    public function getCard(Request $request, User $user, string $cardId)
    {
        if (currentUser()->isNot($user) && currentUser()->isNotAdmin()) {
            return response()->json(['status' => false], ResponseCode::HTTP_FORBIDDEN);
        }

        StrictValidator::check(['card_id' => $cardId], [
            'card_id' => 'required|regex:/^card_.*/',
        ]);

        if (!$card = $user->getCard($cardId)) {
            return response()->json([], ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        $this->resource_name = "users/{$user->id}/card";

        return $this->baseTransformItem($card, null, new CreditCardTransformer)->respond(ResponseCode::HTTP_CREATED);
    }

    public function getCards(Request $request, User $user)
    {
        if (currentUser()->isNot($user) && currentUser()->isNotAdmin()) {
            return response()->json(['status' => false], ResponseCode::HTTP_FORBIDDEN);
        }

        return response()->json(['cards' => $user->getCards()->toArray()]);
    }
}
