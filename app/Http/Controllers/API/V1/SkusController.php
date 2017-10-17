<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\LabTestInformation;
use App\Models\SKU;
use App\Transformers\V1\SKUTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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
        $skuCollection = request()->has('filter')
            ? SKU::where('item_type', request('filter'))->get()
            : SKU::all();
        $skuCollection->load('labTestInformation');
        return $this->baseTransformCollection($skuCollection, request('include'))->respond();
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
            'cost' => 'required',
            'description' => 'required',
            'image' => 'required',
            'sample' => 'required',
            'quote' => 'required',
            'lab_name' => 'required',
            'published_at' => 'boolean',
        ]);
    
        $sku = new SKU($request->only(['name', 'price', 'cost']));
        
        if (currentUser()->cant('create', $sku)) {
            return $this->respondNotAuthorized("You do not have access to create this SKU");
        }
        
        $sku->save();
        $sku->labTestInformation()->save(new LabTestInformation([
            'lab_name' => $request->get('lab_name'),
            'description' => $request->get('description'),
            'image' => $request->get('image'),
            'sample' => $request->get('sample'),
            'quote' => $request->get('quote'),
            'published_at' => $request->get('published_at') ? Carbon::now() : null
        ]));
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
            'cost' => 'required',
            'description' => 'required',
            'image' => 'required',
            'sample' => 'required',
            'quote' => 'required',
            'lab_name' => 'required',
            'published_at' => 'boolean',
        ]);
    
        try {
            DB::transaction(function () use ($request, $sku) {
                $sku->update($request->only(['name', 'price', 'cost']));
                $sku->labTestInformation()->update([
                    'lab_name' => $request->get('lab_name'),
                    'description' => $request->get('description'),
                    'image' => $request->get('image'),
                    'sample' => $request->get('sample'),
                    'quote' => $request->get('quote'),
                    'published_at' => $request->get('published_at') ? Carbon::now() : null
                ]);
            });
            $sku->refresh();
            $sku->load('labTestInformation');
            return $this->baseTransformItem($sku, request('include'))->respond();
        } catch (\Exception $exception) {
            return $this->respondWithError($exception->getMessage());
        }
    }
    
    public function updateListOrder(Request $request, SKU $sku)
    {
        $this->validate($request, ['list_order' => 'required']);
        
        $sku->labTestInformation()->update(['list_order' => $request->get('list_order')]);
        
        return JsonResponse::create(['status' => 'updated']);
    }
    
    public function delete()
    {
    
    }
}
