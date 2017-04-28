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

    public function show(Practitioner $practitioner)
    {
        $data = $this->baseTransformItem($practitioner);

        if (request()->get('include') == 'availability') {
            $data = $data->addMeta([
                'availability' => $this->availabilityTransformer->transform($practitioner)
            ]);
        }
        return $data->respond();
    }
}
