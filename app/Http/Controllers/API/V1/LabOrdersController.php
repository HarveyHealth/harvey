<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{DiscountCode, LabOrder};
use App\Transformers\V1\LabOrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use ResponseCode;

class LabOrdersController extends BaseAPIController
{
    protected $resource_name = 'lab_order';

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
    public function getAll()
    {
        if (currentUser()->isAdmin()) {
            $builder = LabOrder::make();
        } else {
            $builder = LabOrder::patientOrPractitioner(currentUser());
        }

        $builder = $builder->with('patient.user')->with('practitioner.user')->with('invoice');

        return $this->baseTransformBuilder($builder, request('include'), $this->transformer, request('per_page'))->respond();
    }

    /**
     * @param Request     $request
     * @param LabOrder     $labOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(Request $request, LabOrder $labOrder)
    {
        if (currentUser()->cant('view', $labOrder)) {
            return $this->respondNotAuthorized("You do not have access to view this LabOrder.");
        }

        return $this->baseTransformItem($labOrder, request('include'))->respond();
    }

    /**
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (currentUser()->isNotAdminOrPractitioner()) {
            return $this->respondNotAuthorized('You are not authorized to access this resource.');
        }

        StrictValidator::check($request->all(), [
            'address_1' => 'filled|max:100',
            'address_2' => 'filled|max:100',
            'city' => 'filled|max:100',
            'patient_id' => 'required|exists:patients,id',
            'practitioner_id' => 'required|exists:practitioners,id',
            'shipment_code' => 'filled|string',
            'state' => 'filled|max:2',
            'status' => ['filled', Rule::in(LabOrder::STATUSES)],
            'zip' => 'filled|digits:5|serviceable',
        ], [
            'serviceable' => "Sorry, Lab Orders can't be delivered to that :attribute."
        ]);

        return $this->baseTransformItem(LabOrder::create($request->all())->fresh())->respond();
    }

    /**
     * @param Request     $request
     * @param LabOrder     $labOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, LabOrder $labOrder)
    {
        if (currentUser()->cant('update', $labOrder) || $labOrder->isComplete()) {
            return $this->respondNotAuthorized('You do not have access to update this LabOrder.');
        }

        StrictValidator::checkUpdate($request->all(), [
            'shipment_code' => 'filled|string',
            'shippo_id' => 'string',
            'address_1' => "sometimes|order_was_not_shipped:{$labOrder->id}",
            'address_2' => "sometimes|order_was_not_shipped:{$labOrder->id}",
            'city' => "sometimes|order_was_not_shipped:{$labOrder->id}",
            'discount_code' => 'sometimes|string|max:24',
            'shipment_code' => 'filled|string',
            'state' => "sometimes|order_was_not_shipped:{$labOrder->id}",
            'zip' => "sometimes|digits:5|order_was_not_shipped:{$labOrder->id}",
        ]);

        $labOrder->update($request->all());
        $labOrder->setDiscountCode(currentUser(), $request->input('discount_code'), 'lab-test');

        return $this->baseTransformItem($labOrder, request('include'))->respond();
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

        if (!$labOrder->delete()) {
            return $this->baseTransformItem($labOrder)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }

    /**
     * @param LabOrder $labOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function ship(LabOrder $labOrder)
    {
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized("You do not have access to ship this LabOrder");
        }

        if (!$labOrder->ship()) {
            return response()->json([], ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        return $this->baseTransformItem($labOrder->fresh(), request('include'))->respond();
    }
}
