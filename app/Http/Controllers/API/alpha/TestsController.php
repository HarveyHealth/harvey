<?php

namespace App\Http\Controllers\API\alpha;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\alpha\Transformers\TestTransformer;

class TestsController extends BaseAPIController
{
    protected $transformer;
    
    public function __construct(TestTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    
    /**
     * @api {get} /tests/:id View a Test by id
     * @apiName GetTest
     * @apiGroup Tests
     *
     * @apiSuccess {Object} data Wrapper for data content
     * @apiSuccess {Number} data.id Test id
     * @apiSuccess {String} data.results_key Presigned URL that will expire in 1 hour
     * @apiSuccess {Number} data.sku_id Test SKU
     * @apiSuccessExample {json} Success-Response:
     *    {
     *      "data": {
     *          "id": 1,
     *          "results_key": "https://s3.amazonaws.com/harvey-develop/2/1/results.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAJ4LAGPVZAUCRPKYQ%2F20170206%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20170206T185028Z&X-Amz-SignedHeaders=host&X-Amz-Expires=3600&X-Amz-Signature=8be51ebefc3265cc58d3fa711b2c63ddf48e5e709ef8fa72f046a9667489f9c6",
     *          "sku_id": 1
     *      },
     *      "meta": null
     *
     * */
    public function show(Test $test)
    {
        $transformed_test = $this->transformer->transform($test);
        return $this->respond($transformed_test);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * @api {post} /tests/:id/results Upload the results of a test
     * @apiName UploadTestResults
     * @apiGroup Tests
     *
     * @apiParam {File} file PDF test result file
     *
     * @apiSuccess {Object} data Wrapper for data content
     * @apiSuccess {Number} data.id Test id
     * @apiSuccess {String} data.results_key Presigned URL that will expire in 1 hour
     * @apiSuccess {Number} data.sku_id Test SKU
     * @apiSuccess {Object} meta Wrapper for data content
     * @apiSuccess {Boolean} uploaded
     * @apiSuccessExample {json} Success-Response:
     *    {
     *      "data": {
     *          "id": 1,
     *          "results_key": "https://s3.amazonaws.com/harvey-develop/2/1/results.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAJ4LAGPVZAUCRPKYQ%2F20170206%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Date=20170206T185028Z&X-Amz-SignedHeaders=host&X-Amz-Expires=3600&X-Amz-Signature=8be51ebefc3265cc58d3fa711b2c63ddf48e5e709ef8fa72f046a9667489f9c6",
     *          "sku_id": 1
     *      },
     *      "meta": {
     *          "uploaded": true
     *      }
     *
     * */
    public function results(Test $test, Request $request)
    {
        $this->validate($request, [
           'file' => 'required|file|mimetypes:application/pdf'
        ]);
        
        $relative_path = "$test->patient_user_id/$test->id";
        
        try {
            Storage::disk('s3')->putFileAs(
                $relative_path, // Directory path relative to bucket
                $request->file('file'),  // Uploaded file from request
                'results.pdf', // filename,
                ['ContentType' => $request->file('file')->getMimeType()]
            );
            
            $test->results_key = "$relative_path/results.pdf";
            $test->save();
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
        
        $transformed_test = $this->transformer->transform($test);
        return $this->respond($transformed_test, ['uploaded' => true]);
    }
}
