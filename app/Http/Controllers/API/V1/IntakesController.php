<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\Intake;
use App\Transformers\V1\IntakeTransformer;
use Illuminate\Http\Request;
use Exception, ResponseCode;

class IntakesController extends BaseAPIController
{
    protected $resource_name = 'intakes';

    /**
     * IntakesController constructor.
     * @param IntakeTransformer $transformer
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

    public function update(Request $request, Intake $intake)
    {
        if (currentUser()->cant('update', $intake)) {
            return $this->respondNotAuthorized('You do not have access to update this Intake.');
        }

        StrictValidator::checkUpdate($request->all(), [
            'notes' => 'filled|string|max:4096',
        ]);

        $intake->update($request->all());

        return $this->baseTransformItem($intake, request('include'))->respond();
    }
}
