<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Practitioner;
use App\Transformers\V1\PractitionerAvailabilityTransformer;
use App\Transformers\V1\PractitionerTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PractitionersController extends BaseAPIController
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
            'description' => 'max:255',
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
    
    public function imageUpload(Request $request, Practitioner $practitioner)
    {
        if (auth()->user()->cant('update', $practitioner)) {
            return $this->respondNotAuthorized("You do not have access to modify the practitioner with id {$practitioner->id}.");
        }

        try{
            $image = $request->file('image');
            $imageDir = $request->get('type') == 'header' ? 'header/' : 'practitioner-profile/';
            $imagePath = $imageDir . time() . $image->getFilename() . '.' . $image->getClientOriginalExtension();
            Storage::cloud()->put($imagePath, file_get_contents($image), 'public');
        } catch (\Exception $exception) {
            return $this->respondWithError('Unable to upload image. Please try again later');
        }

        if($request->get('type') == 'header') {
            $practitioner->update([
                'background_picture_url' => Storage::cloud()->url($imagePath)
            ]);
        } else {
            $practitioner->update([
                'picture_url' => Storage::cloud()->url($imagePath)
            ]);
        }

        return $this->baseTransformItem($practitioner)->respond();
    }
}
