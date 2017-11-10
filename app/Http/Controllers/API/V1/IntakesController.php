<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Patient;
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
        if (currentUser()->isNotAdminOrPractitioner()) {
            return $this->respondNotAuthorized('You do not have access to retrieve Intake forms.');
        }

        $builder = Patient::make();

        if (is_numeric(request('per_page'))) {
            $paginator = $builder->paginate((int) request('per_page'));
            $paginator->appends(array_diff_key(request()->all(), array_flip(['page'])));
            $collection = $paginator->items();
            $paginationAdapter =  new IlluminatePaginatorAdapter($paginator);
        } else {
            $collection = $builder->get();
            $paginationAdapter = null;
        }

        $collection = $collection->map(function ($i) { return $i->getIntakeData(); })->filter();

        return $this->baseTransformCollection($collection, request('include'), $this->transformer, $paginationAdapter)->respond();
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
