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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {
        return $this->transformer->transform($test);
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
     * @param Test    $test
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
