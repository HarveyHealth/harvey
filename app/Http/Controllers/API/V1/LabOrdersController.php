<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\LabOrder;
use App\Transformers\V1\LabOrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use ResponseCode;

class LabOrdersController extends BaseAPIController
{
    protected $resource_name = 'lab_orders';

    /**
     * LabOrdersController constructor.
     * @param LabOrderTransformer $transformer
     */
    public function __construct(LabOrderTransformer $transformer)
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

        $builder = LabOrder::make();

        return $this->baseTransformBuilder($builder, request('include'), new LabOrderTransformer, request('per_page'))->respond();
    }

    /**
     * @param Request     $request
     * @param LabOrder     $labOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, LabOrder $labOrder)
    {
        if (currentUser()->cant('view', $labOrder)) {
            return $this->respondNotAuthorized("You do not have access to view this LabOrder.");
        }

        return $this->baseTransformItem($labOrder)->respond();
    }

    /**
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = StrictValidator::check($request->all(), [
            'practitioner_id' => 'required|exists:practitioners,id',
            'patient_id' => 'required|exists:patients,id',
            'status' => ['filled', Rule::in(LabOrder::STATUSES)],
            'shipment_code' => 'string',
        ]);

        return $this->baseTransformItem(LabOrder::create($request->all())->fresh())->respond();
    }

    public function update(Request $request, LabOrder $labOrder)
    {
        if (currentUser()->cant('update', $labOrder)) {
            return $this->respondNotAuthorized('You do not have access to update this LabOrder.');
        }

        StrictValidator::checkUpdate($request->all(), [
            'shipment_code' => 'string',
        ]);

        $labOrder->update($request->all());

        return $this->baseTransformItem($labOrder)->respond();
    }

    /**
     * @param LabOrder $labOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(LabOrder $labOrder)
    {
        if (currentUser()->cant('delete', $labOrder)) {
            return $this->respondNotAuthorized("You do not have access to delete this LabOrder");
        }

        $labOrder->delete();

        return $this->baseTransformItem($labOrder)->addMeta(['deleted' => true])->respond(ResponseCode::HTTP_NO_CONTENT);
    }
}
