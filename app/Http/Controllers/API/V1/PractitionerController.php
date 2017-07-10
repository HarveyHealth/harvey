<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\PractitionerAvailability;
use App\Models\Practitioner;
use App\Transformers\V1\PractitionerAvailabilityTransformer;
use App\Transformers\V1\PractitionerTransformer;
use Illuminate\Http\Request;

class PractitionerController extends BaseAPIController
{
    protected $resource_name = 'practitioner';

    public function __construct(PractitionerTransformer $transformer, PractitionerAvailabilityTransformer $availabilityTransformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
        $this->availabilityTransformer = $availabilityTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $include = currentUser()->isAdminOrPractitioner() ? request('include') : null;

        return $this->baseTransformBuilder(Practitioner::make(), $include, $this->transformer, request('per_page'))->respond();
    }

    public function show(Practitioner $practitioner)
    {
        $include = currentUser()->isAdminOrPractitioner() ? request('include') : null;

        $data = $this->baseTransformItem($practitioner, $include);

        if (in_array('availability', explode(',', request('include')))) {
            $data = $data->addMeta([
                'availability' => $this->availabilityTransformer->transform($practitioner)
            ]);
        }

        return $data->respond();
    }
    
    public function update(Request $request, User $user)
    {
        StrictValidator::check($request->all(), [
            'description' => 'max:100',
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
//        if (auth()->user()->cant('update', $user)) {
//            return $this->respondNotAuthorized("You do not have access to modify the user with id {$user->id}.");
//        }
//
//        try{
//            $image = $request->file('image');
//            $imagePath = 'profile-images/' . time() . $image->getFilename() . '.' . $image->getClientOriginalExtension();
//            $s3 = Storage::cloud()->put($imagePath, file_get_contents($image), 'public');
//        } catch (\Exception $exception) {
//            return $this->respondWithError('Unable to upload image. Please try again later');
//        }
//
//        if($request->get('type') == 'header') {
//            $user->update([
//                'background_image_url' => Storage::cloud()->url($imagePath)
//            ]);
//        } else {
//            $user->update([
//                'picture_url' => Storage::cloud()->url($imagePath)
//            ]);
//        }
//
//        return $this->baseTransformItem($user)->respond();
    }
}
