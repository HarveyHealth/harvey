<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Patient;
use App\Transformers\V1\IntakeTransformer;
use Illuminate\Http\Request;
use Exception, ResponseCode;

class IntakesController extends BaseAPIController
{
    protected $resource_name = 'intake';

    /**
     * AttachmentsController constructor.
     * @param AttachmentTransformer $transformer
     */
    public function __construct(IntakeTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }


    public function getAll(Request $request)
    {
        if (currentUser()->isNotAdminOrPractitioner()) {
            return $this->respondNotAuthorized('You do not have access to retrieve Intake forms.');
        }

        $intakes = Patient::all()->map(function ($i) { return $i->getIntakeData(); })->filter();

        return $this->baseTransformCollection($intakes)->respond();
    }

    public function getOne(Request $request, string $token)
    {
        if (currentUser()->isNotAdminOrPractitioner()) {
            return $this->respondNotAuthorized('You do not have access to retrieve this Intake form.');
        }

        if (empty($patient = Patient::where('intake_token', $token)->first())) {
            return $this->respondNotFound("Can't find a Patient with that Intake token assigned.");
        }

        return $this->baseTransformItem($patient->getIntakeData())->respond();
    }
}
