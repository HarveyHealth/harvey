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
            'description' => 'required|max:800',
            'image' => 'required',
            'sample' => 'required',
            'quote' => 'required|max:200',
            'lab_name' => 'required',
            'visibility_id' => 'required|integer',
        ]);

        $sku = new SKU($request->only(['name', 'price', 'cost']));

        if (currentUser()->cant('create', $sku)) {
            return $this->respondNotAuthorized("You do not have access to create this SKU");
        }

        $sku->save();
        $sku->labTestInformation()->save(new LabTestInformation(
            $request->only(['lab_name', 'description', 'image', 'sample', 'quote', 'visibility_id'])
        ));
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
            'description' => 'required|max:800',
            'image' => 'required',
            'sample' => 'required',
            'quote' => 'required|max:200',
            'lab_name' => 'required',
            'visibility_id' => 'required|integer',
        ]);

        try {
            DB::transaction(function () use ($request, $sku) {
                $sku->update($request->only(['name', 'price', 'cost']));
                $sku->labTestInformation->fill(
                    $request->only(['lab_name', 'description', 'image', 'sample', 'quote', 'visibility_id'])
                );
                $sku->labTestInformation->save();
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
