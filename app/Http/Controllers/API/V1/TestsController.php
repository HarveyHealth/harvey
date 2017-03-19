<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Test;
use App\Transformers\V1\TestTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestsController extends BaseAPIController
{
    /**
     * @var TestTransformer
     */
    protected $transformer;
    
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
            return fractal()->item($test)
                ->withResourceName('tests')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        } else {
            return $this->respondNotAuthorized('Unauthorized to view this resource.');
        }
    }
    
    /**
     * @param Test    $test
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function results(Test $test, Request $request)
    {
        $this->validate($request, [
            'file' => 'required'
        ]);
        
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
            
            return fractal()->item($test)
                ->withResourceName('tests')
                ->transformWith($this->transformer)
                ->serializeWith($this->serializer)
                ->respond();
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
