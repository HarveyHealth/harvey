<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
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
    
    public function update(Request $request, Practitioner $practitioner)
    {
        StrictValidator::check($request->except(['name']), [
            'description' => 'max:300',
            'license_title' => 'max:3',
            'license_number' => 'max:10',
            'license_state' => 'max:20',
            'school' => 'max:255',
            'graduated_year' => 'max:10',
            'specialty_1' => 'max:255',
            'specialty_2' => 'max:255',
            'specialty_3' => 'max:255',
            'specialty_4' => 'max:255',
            'specialty_5' => 'max:255'
        ]);
        
        if (auth()->user()->can('update', $practitioner)) {
            $practitioner->update($request->except(['name', 'rate', 'user_id']));
            
            return $this->baseTransformItem($practitioner)->respond();
        } else {
            return $this->respondNotAuthorized("You do not have access to modify the practitioner with id {$practitioner->id}.");
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
