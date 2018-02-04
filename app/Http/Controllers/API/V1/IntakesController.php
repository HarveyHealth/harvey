<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Intake;
use App\Transformers\V1\IntakeTransformer;
use Illuminate\Http\Request;
use Exception, ResponseCode;

class IntakesController extends BaseAPIController
{
    protected $resource_name = 'intakes';

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
        if (currentUser()->isNotPractitioner()) {
            return $this->respondNotAuthorized('You do not have access to retrieve Intake forms.');
        }

        return $this->baseTransformBuilder(Intake::make(), request('include'), $this->transformer, request('per_page'))->respond();
    }

    public function getOne(Request $request, Intake $intake)
    {
        if (currentUser()->cant('view', $intake)) {
            return $this->respondNotAuthorized('You do not have access to retrieve this Intake form.');
        }

        return $this->baseTransformItem($intake, request('include'))->respond();
    }
}
