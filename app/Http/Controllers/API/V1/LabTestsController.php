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
     * @param LabTest     $lab_test
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, LabTest $lab_test)
    {
        if (currentUser()->cant('view', $lab_test)) {
            return $this->respondNotAuthorized('You do not have access to view this LabTest.');
        }

        return $this->baseTransformItem($lab_test, request('include'))->respond();
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

    public function update(Request $request, LabTest $lab_test)
    {
        if (currentUser()->cant('update', $lab_test)) {
            return $this->respondNotAuthorized('You do not have access to update this LabTest.');
        }

        StrictValidator::checkUpdate($request->all(), [
            'status' => ['filled', Rule::in(LabTest::STATUSES)],
            'shipment_code' => 'filled|string',
        ]);

        $lab_test->update($request->all());

        if (currentUser()->isAdminOrPractitioner()) {
            $lab_test->labOrder->setStatus()->save();
        }

        return $this->baseTransformItem($lab_test, request('include'))->respond();
    }

    /**
     * @param LabTest $lab_test
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(LabTest $lab_test)
    {
        if (currentUser()->cant('delete', $lab_test)) {
            return $this->respondNotAuthorized("You do not have access to delete this LabTest");
        }

        if (!$lab_test->delete()) {
            return $this->baseTransformItem($lab_test)->respond(ResponseCode::HTTP_CONFLICT);
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
