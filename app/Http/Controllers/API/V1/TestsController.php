<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Test;
use App\Transformers\V1\TestTransformer;
use Crell\ApiProblem\ApiProblem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Validator;

class TestsController extends BaseAPIController
{
    protected $resource_name = 'tests';

    /**
     * TestsController constructor.
     * @param TestTransformer $transformer
     */
    public function __construct(TestTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    /**
     * @param Test $test
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Test $test)
    {
        if (auth()->user()->can('view', $test)) {
            return $this->baseTransformItem($test)->respond();
        } else {
            $problem = new ApiProblem();
            $problem->setDetail('You do not have access to view this test.');
            return $this->respondNotAuthorized($problem);
        }
    }

    /**
     * @param Test    $test
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function results(Test $test, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required'
        ]);

        if ($validator->fails()) {
            $problem = new ApiProblem();
            $problem->setDetail($validator->errors()->first());
            return $this->respondBadRequest($problem);
        }

        $relative_path = "$test->patient_id/$test->id";

        try {
            Storage::disk('s3')->putFileAs(
                $relative_path, // Directory path relative to bucket
                $request->file('file'),  // Uploaded file from request
                'results.pdf', // filename,
                ['ContentType' => $request->file('file')->getMimeType()]
            );

            $test->results_key = "$relative_path/results.pdf";
            $test->save();
    
            return $this->baseTransformItem($test)->respond();
        } catch (\Exception $e) {
            $problem = new ApiProblem();
            $problem->setDetail($e->getMessage());
            return $this->respondUnprocessable($problem);
        }
    }
}
