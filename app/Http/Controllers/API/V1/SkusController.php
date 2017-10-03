<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\LabTestInformation;
use App\Models\SKU;
use App\Transformers\V1\SKUTransformer;
use Illuminate\Http\Request;
use DB;

class SkusController extends BaseAPIController
{
    protected $resource_name = 'skus';
    
    public function __construct(SKUTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }
    
    public function index()
    {
        return $this->baseTransformCollection(SKU::all(), request('include'))->respond();
    }
    
    public function indexLabTests()
    {
        return $this->baseTransformCollection(SKU::LabTests()->get(), request('include'))->respond();
    }
    
    public function show(Sku $sku)
    {
        if (currentUser()->cant('view', $sku)) {
            return $this->respondNotAuthorized("You do not have access to view this SKU.");
        }
    
        return $this->baseTransformItem($sku, request('include'))->respond();
    }
    
    public function store(Request $request)
    {
        StrictValidator::check($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'required',
            'sample' => 'required',
            'quote' => 'required',
            'lab_name' => 'required',
        ]);
    
        $sku = new SKU($request->only(['name', 'price']));
        
        if (currentUser()->cant('create', $sku)) {
            return $this->respondNotAuthorized("You do not have access to create this SKU");
        }
        
        $sku->save();
        $sku->labTestInformation()->save(new LabTestInformation($request->only(['description', 'image', 'sample', 'quote', 'lab_name'])));
        $sku->refresh();
        
        return $this->baseTransformItem($sku, request('include'))->respond();
    }
    
    public function update(Request $request, SKU $sku)
    {
        if (currentUser()->cant('update', $sku)) {
            return $this->respondNotAuthorized("You do not have access to modify this SKU");
        }
        
        StrictValidator::check($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'required',
            'sample' => 'required',
            'quote' => 'required',
            'lab_name' => 'required',
        ]);
    
        try {
            DB::transaction(function () use ($request, $sku) {
                $sku->update($request->only(['name', 'price']));
                $sku->labTestInformation()->update($request->only(['description', 'image', 'sample', 'quote']));
            });
            $sku->refresh();
            return $this->baseTransformItem($sku, request('include'))->respond();
        } catch (\Exception $exception) {
            return $this->respondWithError($exception->getMessage());
        }
    }
    
    public function delete()
    {
    
    }
}
