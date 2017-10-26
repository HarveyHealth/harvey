<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\{LabTest, LabTestInformation};
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
     * @param LabTest     $labTest
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOne(Request $request, LabTest $labTest)
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
    public function delete(Request $request, LabTest $labTest)
    {
        if (currentUser()->cant('delete', $labTest)) {
            return $this->respondNotAuthorized("You do not have access to delete this LabTest");
        }

        if (!$labTest->delete()) {
            return $this->baseTransformItem($labTest)->respond(ResponseCode::HTTP_CONFLICT);
        }

        return response()->json([], ResponseCode::HTTP_NO_CONTENT);
    }

    public function getResults(Request $request, LabTest $labTest)
    {
        if (currentUser()->cant('view', $labTest)) {
            return $this->respondNotAuthorized('You do not have access to view this LabTest results.');
        }

        return $this->baseTransformBuilder($labTest->results(), request('include'), new LabTestResultTransformer, request('per_page'))->respond();
    }


    public function storeResult(Request $request, LabTest $labTest)
    {
        if (currentUser()->cant('update', $labTest)) {
            return $this->respondNotAuthorized('You do not have access to submit results for this LabTest.');
        }

        $validator = StrictValidator::check($request->all(), [
            'file' => 'required|mimes:pdf',
            'notes' => 'string|max:1024',
        ]);

        $relative_path = "{$labTest->patient->user->id}";

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path,
                $request->file('file'),
                $fileName = "LabTestResult_{$labTest->results()->withoutGlobalScopes()->count()}.pdf",
                [
                    'visibility' => 'private',
                    'ContentType' => $request->file('file')->getMimeType(),
                ]
            );

            $labTest->results()->create([
                'key' => "{$relative_path}/{$fileName}",
                'notes' => request('notes'),
            ]);

            return $this->baseTransformItem($labTest->fresh(), 'results')->respond();
        } catch (Exception $e) {
            return $this->respondUnprocessable($e->getMessage());
        }
    }

    public function deleteResult(Request $request, LabTest $labTest, LabTestResult $labTestResult)
    {
        if (currentUser()->cant('delete', $labTest) || !$labTest->results()->find($labTestResult->id)) {
            return $this->respondNotAuthorized("You do not have access to delete this LabTest result");
        }

        if (!$labTestResult->delete()) {
            return $this->baseTransformItem($labTestResult)->respond(ResponseCode::HTTP_CONFLICT);
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
            $scope = str_plural($user->type);
            $builder = $builder->$scope();
        } else {
            $builder = $builder->public();
        }

        return $this->baseTransformBuilder($builder, request('include'), new LabTestInformationTransformer, request('per_page'))->respond();
    }

}
