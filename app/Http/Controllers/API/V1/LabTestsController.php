<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{LabTest, LabTestInformation, LabTestResult};
use App\Transformers\V1\{LabTestTransformer, LabTestInformationTransformer, LabTestResultTransformer};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use League\Fractal\Serializer\JsonApiSerializer;
use Exception, ResponseCode, Storage;

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
    public function getAll()
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
    public function getOne(Request $request, LabTest $lab_test)
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
    public function delete(Request $request, LabTest $lab_test)
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
    public function getInformation()
    {
        $this->serializer = new JsonApiSerializer();

        $builder = LabTestInformation::with('sku');

        if ($user = currentUser()) {
            $scope = str_plural($user->type);
            $builder = $builder->$scope();
        } else {
            $builder = $builder->public();
        }

        return $this->baseTransformBuilder($builder, request('include'), new LabTestInformationTransformer, request('per_page'))->respond();
    }

    public function getOneResult(Request $request, LabTestResult $lab_test_result)
    {
        if (currentUser()->cant('view', $lab_test_result->labTest)) {
            return $this->respondNotAuthorized('You do not have access to view this LabTest result.');
        }

        $this->resource_name = 'lab_tests_results';

        return $this->baseTransformItem($lab_test_result, request('include'), new LabTestResultTransformer, request('per_page'))->respond();
    }

    public function storeResult(Request $request, LabTest $lab_test)
    {
        if (currentUser()->cant('update', $lab_test)) {
            return $this->respondNotAuthorized('You do not have access to submit results for this LabTest.');
        }

        $validator = StrictValidator::check($request->all(), [
            'file' => 'required|mimes:pdf',
            'notes' => 'string|max:1024',
        ]);

        $relative_path = "{$lab_test->patient->user->id}";

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path,
                $request->file('file'),
                $fileName = "LabTestResult_{$lab_test->results()->withoutGlobalScopes()->count()}.pdf",
                [
                    'visibility' => 'private',
                    'ContentType' => $request->file('file')->getMimeType(),
                ]
            );

            $lab_test->results()->create([
                'key' => "{$relative_path}/{$fileName}",
                'notes' => request('notes'),
            ]);

            $this->resource_name = 'lab_tests_results';

            return $this->baseTransformItem($lab_test->fresh(), 'results')->respond();
        } catch (Exception $e) {
            return $this->respondUnprocessable($e->getMessage());
        }
    }

    public function deleteResult(Request $request, LabTestResult $lab_test_result)
    {
        if (currentUser()->cant('delete', $lab_test_result->labTest)) {
            return $this->respondNotAuthorized("You do not have access to delete this LabTest result");
        }

        if (!$lab_test_result->delete()) {
            return $this->baseTransformItem($lab_test_result)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }

    /**
     * @param LabTest $lab_test
     * @return \Illuminate\Http\JsonResponse
     */
    public function track(Request $request, LabTest $lab_test)
    {
        if (currentUser()->isNotAdmin()) {
            return $this->respondNotAuthorized("You do not have access to track this LabTest");
        }

        if (empty($lab_test->shipment_code) || empty($lab_test->carrier)) {
            return response()->json([], ResponseCode::HTTP_SERVICE_UNAVAILABLE);
        }

        $output = Cache::remember("track_for_shippo_id_{$lab_test->shippo_id}", TimeInterval::hours(1)->toMinutes(), function () use ($lab_test) {
            return Shippo_Track::create(['carrier' => $lab_test->carrier, 'tracking_number' => $lab_test->shipment_code])->__toArray(true)
        });

        return response()->json($output, ResponseCode::HTTP_OK);
    }
}
