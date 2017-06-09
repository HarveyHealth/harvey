<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\LabTest;
use App\Transformers\V1\LabTestTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use ResponseCode;

class LabTestsController extends BaseAPIController
{
    protected $resource_name = 'lab_tests';

    /**
     * LabTestsController constructor.
     * @param LabTestTransformer $transformer
     */
    public function __construct(LabTestTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized('You are not authorized to access this resource.');
        }

        $builder = LabTest::make();

        return $this->baseTransformBuilder($builder, request('include'), new LabTestTransformer, request('per_page'))->respond();
    }

    /**
     * @param Request     $request
     * @param LabTest     $labTest
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, LabTest $labTest)
    {
        if (currentUser()->cant('view', $labTest)) {
            return $this->respondNotAuthorized('You do not have access to view this LabTest.');
        }

        return $this->baseTransformItem($labTest)->respond();
    }

    /**
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized('You are not authorized to access this resource.');
        }

        $validator = StrictValidator::check($request->all(), [
            'lab_order_id' => 'required|exists:lab_orders,id',
            'sku_id' => 'required|exists:skus,id',
            'status' => ['filled', Rule::in(LabTest::STATUSES)],
            'results_url' => 'url',
            'shipment_code' => 'string',
        ]);

        return $this->baseTransformItem(LabTest::create($request->all())->fresh())->respond();
    }

    public function update(Request $request, LabTest $labTest)
    {
        if (currentUser()->cant('update', $labTest)) {
            return $this->respondNotAuthorized('You do not have access to update this LabTest.');
        }

        StrictValidator::checkUpdate($request->all(), [
            'status' => ['filled', Rule::in(LabTest::STATUSES)],
        ]);

        $labTest->update($request->all());

        return $this->baseTransformItem($labTest)->respond();
    }

    /**
     * @param LabTest $labTest
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(LabTest $labTest)
    {
        if (currentUser()->cant('delete', $labTest)) {
            return $this->respondNotAuthorized("You do not have access to delete this LabTest");
        }

        if (!$labTest->delete()) {
            return $this->baseTransformItem($labTest)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }
}
