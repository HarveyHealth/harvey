<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\TimeInterval;
use App\Lib\Validation\StrictValidator;
use App\Models\{DiscountCode, LabOrder};
use App\Transformers\V1\LabOrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Cache, ResponseCode, Shippo_Track, Shippo_Transaction;

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
     * @param LabOrder     $lab_order
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(Request $request, LabOrder $lab_order)
    {
        if (currentUser()->cant('view', $lab_order)) {
            return $this->respondNotAuthorized("You do not have access to view this LabOrder.");
        }

        return $this->baseTransformItem($lab_order, request('include'))->respond();
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
     * @param LabOrder     $lab_order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, LabOrder $lab_order)
    {
        if (currentUser()->cant('update', $lab_order) || $lab_order->isComplete()) {
            return $this->respondNotAuthorized('You do not have access to update this LabOrder.');
        }

        StrictValidator::checkUpdate($request->all(), [
            'shipment_code' => 'filled|string',
            'shippo_id' => 'string',
            'address_1' => "sometimes|order_was_not_shipped:{$lab_order->id}",
            'address_2' => "sometimes|order_was_not_shipped:{$lab_order->id}",
            'city' => "sometimes|order_was_not_shipped:{$lab_order->id}",
            'discount_code' => 'sometimes|string|max:24',
            'shipment_code' => 'filled|string',
            'state' => "sometimes|order_was_not_shipped:{$lab_order->id}",
            'zip' => "sometimes|digits:5|order_was_not_shipped:{$lab_order->id}",
        ]);

        $lab_order->update($request->all());
        $lab_order->setDiscountCode(currentUser(), $request->input('discount_code'), 'lab-test');

        return $this->baseTransformItem($lab_order, request('include'))->respond();
    }

    /**
     * @param LabOrder $lab_order
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(LabOrder $lab_order)
    {
        if (currentUser()->cant('delete', $lab_order)) {
            return $this->respondNotAuthorized("You do not have access to delete this LabOrder");
        }

        if (!$lab_order->delete()) {
            return $this->baseTransformItem($lab_order)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }

    /**
     * @param LabOrder $lab_order
     * @return \Illuminate\Http\JsonResponse
     */
    public function track(Request $request, LabOrder $lab_order)
    {
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized("You do not have access to track this LabOrder");
        }

        if (empty($lab_order->shipment_code) || empty($lab_order->carrier)) {
            return response()->json([], ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        $output = Cache::remember("track_for_shippo_id_{$lab_order->shippo_id}", TimeInterval::hours(1)->toMinutes(), function () use ($lab_order) {
            return Shippo_Track::create(['carrier' => $lab_order->carrier, 'tracking_number' => $lab_order->shipment_code])->__toArray(true);
        });

        return response()->json($output, ResponseCode::HTTP_OK);
    }

    /**
     * @param LabOrder $lab_order
     * @return \Illuminate\Http\JsonResponse
     */
    public function ship(Request $request, LabOrder $lab_order)
    {
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized("You do not have access to ship this LabOrder");
        }

        StrictValidator::check($request->all(), [
            'carrier' => ['filled', Rule::in(LabOrder::ALLOWED_CARRIERS)],
            'servicelevel_token' => ['filled', Rule::in(LabOrder::ALLOWED_SERVICELEVEL_TOKENS)],
        ]);

        $lab_order->ship($request->input('carrier'), $request->input('servicelevel_token'));

        return $this->baseTransformItem($lab_order->fresh(), request('include'))->respond();
    }
}
