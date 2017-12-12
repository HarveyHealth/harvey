<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{Patient, Practitioner};
use App\Transformers\V1\{PatientTransformer, PractitionerTransformer, PractitionerAvailabilityTransformer};
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
    public function getAll()
    {
        $include = currentUser()->isAdminOrPractitioner() ? request('include') : null;

        return $this->baseTransformBuilder(Practitioner::make(), $include, $this->transformer, request('per_page'))->respond();
    }

    public function getOne(Practitioner $practitioner)
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
        if (auth()->user()->cant('update', $practitioner)) {
            return $this->respondNotAuthorized("You do not have access to modify the practitioner with id {$practitioner->id}.");
        }

        StrictValidator::check($request->except(['name']), [
            'description' => 'max:1000',
            'licenses' => 'array',
            'licenses.*.number' => 'max:10|required_with:licenses.*.state,licenses.*.title|alpha_num',
            'licenses.*.state' => 'max:2|required_with:licenses.*.title,licenses.*.number',
            'licenses.*.title' => 'max:3|required_with:licenses.*.number,licenses.*.state',
            'school' => 'max:255',
            'graduated_year' => 'digits:4',
            'specialty' => 'array',
        ]);

        $practitioner->update($request->except(['name', 'rate', 'user_id']));

        return $this->baseTransformItem($practitioner)->respond();
    }

    public function profileImageUpload(Request $request, Practitioner $practitioner)
    {
        if (auth()->user()->cant('update', $practitioner)) {
            return $this->respondNotAuthorized("You do not have access to modify the practitioner with id {$practitioner->id}.");
        }

        StrictValidator::check($request->only('image'), [
            'image' => 'required|dimensions:max_width=300,max_height=300',
        ]);

        try {
            $image = $request->file('image');
            $imageDir = 'practitioner-images/';
            $imagePath = $imageDir . time() . $image->getFilename() . '.' . $image->getClientOriginalExtension();
            Storage::cloud()->put($imagePath, file_get_contents($image), 'public');
        } catch (\Exception $exception) {
            return $this->respondWithError('Unable to upload profile image. Please try again later');
        }

        $practitioner->update(['picture_url' => Storage::cloud()->url($imagePath)]);

        return $this->baseTransformItem($practitioner)->respond();
    }

    public function backgroundImageUpload(Request $request, Practitioner $practitioner)
    {
        if (auth()->user()->cant('update', $practitioner)) {
            return $this->respondNotAuthorized("You do not have access to modify the practitioner with id {$practitioner->id}.");
        }

        StrictValidator::check($request->only('image'), [
            'image' => 'required|dimensions:max_width=400,max_height=120',
        ]);

        try {
            $image = $request->file('image');
            $imageDir = 'header/';
            $imagePath = $imageDir . time() . $image->getFilename() . '.' . $image->getClientOriginalExtension();
            Storage::cloud()->put($imagePath, file_get_contents($image), 'public');
        } catch (\Exception $exception) {
            return $this->respondWithError('Unable to upload background image. Please try again later');
        }

        $practitioner->update(['background_picture_url' => Storage::cloud()->url($imagePath)]);

        return $this->baseTransformItem($practitioner)->respond();
    }

    public function getServiceablePatients(Request $request, Practitioner $practitioner)
    {
        return $this->baseTransformBuilder(Patient::serviceablesBy($practitioner), null, new PatientTransformer, request('per_page'))->respond();
    }
}
