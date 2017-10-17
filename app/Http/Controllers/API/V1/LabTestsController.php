<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{LabTest, LabTestInformation};
use App\Transformers\V1\{LabTestTransformer, LabTestInformationTransformer};
use Illuminate\Http\Request;
use Illuminate\Support\Pluralizer;
use Illuminate\Validation\Rule;
use League\Fractal\Serializer\JsonApiSerializer;
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
        if (currentUser()->isAdmin()) {
            $builder = LabTest::make();
        } else {
            $builder = LabTest::patientOrPractitioner(currentUser());
        }

        return $this->baseTransformBuilder($builder->with('sku'), request('include'), $this->transformer, request('per_page'))->respond();
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

        return $this->baseTransformItem($labTest, request('include'))->respond();
    }

    /**
     * @param Request     $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (currentUser()->cant('create', LabTest::class)) {
            return $this->respondNotAuthorized('You are not authorized to access this resource.');
        }

        StrictValidator::check($request->all(), [
            'lab_order_id' => 'required|exists:lab_orders,id',
            'sku_id' => 'required|exists:skus,id',
            'status' => ['filled', Rule::in(LabTest::STATUSES)],
            'results_url' => 'url',
            'shipment_code' => 'string',
        ]);

        return $this->baseTransformItem(LabTest::create($request->all())->fresh(), request('include'))->respond();
    }

    public function update(Request $request, LabTest $labTest)
    {
        if (currentUser()->cant('update', $labTest)) {
            return $this->respondNotAuthorized('You do not have access to update this LabTest.');
        }

        StrictValidator::checkUpdate($request->all(), [
            'status' => ['filled', Rule::in(LabTest::STATUSES)],
            'shipment_code' => 'filled|string',
        ]);

        $labTest->update($request->all());

        return $this->baseTransformItem($labTest, request('include'))->respond();
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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function information()
    {
        $this->serializer = new JsonApiSerializer();

        $builder = LabTestInformation::with('sku');

        if ($user = currentUser()) {
            $scope = Pluralizer::plural($user->type);
            $builder = $builder->$scope();
        } else {
            $builder = $builder->public();
        }

        return $this->baseTransformBuilder($builder, request('include'), new LabTestInformationTransformer, request('per_page'))->respond();
    }

}
