<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\SKU;
use Illuminate\Http\Request;

class SkusController extends BaseAPIController
{
    public function index()
    {
    
    }
    
    public function show()
    {
    
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
        ]);
    
    
        $sku = new SKU($request->only(['name', 'price']));
        
        if (currentUser()->cant('create', $sku)) {
            return $this->respondNotAuthorized("You do not have access to create this Sku");
        }
    
        $sku->save();
        
        return $sku;
    }
    
    public function update()
    {
    
    }
    
    public function delete()
    {
    
    }
}
