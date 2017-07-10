<?php

namespace App\Http\Controllers\API\V1;

use App\Events\{OutOfServiceZipCodeRegistered, UserRegistered};
use App\Lib\Validation\StrictValidator;
use App\Models\{Patient, User};
use App\Transformers\V1\UserTransformer;
use Illuminate\Http\Request;
use ResponseCode;
use Validator;
use Illuminate\Support\Facades\Storage;

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
        if (auth()->user()->can('view', $user)) {
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
        StrictValidator::check($request->all(), [
            'first_name' => 'max:100',
            'last_name' => 'max:100',
            'email' => 'email|max:150|unique:users',
            'zip' => 'digits:5|serviceable',
            'phone' => 'unique:users'
        ], [
            'serviceable' => 'Sorry, we do not service this :attribute.'
        ]);

        if (auth()->user()->can('update', $user)) {
            $user->update($request->all());

            return $this->baseTransformItem($user)->respond();
        } else {
            return $this->respondNotAuthorized("You do not have access to modify the user with id {$user->id}.");
        }
    }
    
    public function imageUpload(Request $request, User $user)
    {
        if (auth()->user()->cant('update', $user)) {
            return $this->respondNotAuthorized("You do not have access to modify the user with id {$user->id}.");
        }
        
        try{
            $image = $request->file('image');
            $imagePath = 'profile-images/' . time() . $image->getFilename() . '.' . $image->getClientOriginalExtension();
            $s3 = Storage::cloud()->put($imagePath, file_get_contents($image), 'public');
        } catch (\Exception $exception) {
            return $this->respondWithError('Unable to upload image. Please try again later');
        }
        
        if($request->get('type') == 'header') {
            $user->update([
                'background_image_url' => Storage::cloud()->url($imagePath)
            ]);
        } else {
            $user->update([
                'picture_url' => Storage::cloud()->url($imagePath)
            ]);
        }
    
        return $this->baseTransformItem($user)->respond();
    }
}
