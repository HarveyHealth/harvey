<?php

namespace App\Http\Controllers\API\V1;

use App\Transformers\V1\DiscountCodeTransformer;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use App\Lib\Validation\StrictValidator;
use ResponseCode;

class DiscountCodesController extends BaseAPIController
{
    /**
     * LabOrdersController constructor.
     * @param DiscountCodeTransformer $transformer
     */
    public function __construct(DiscountCodeTransformer $transformer)
    {
        parent::__construct();
        $this->transformer = $transformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        StrictValidator::check($request->all(), [
            'discount_code' => 'required',
            'applies_to' => 'required'
        ]);

        $code = $request->input('discount_code');
        $applies_to = $request->input('applies_to');
        // $user = auth()->user();
        $user = \App\Models\User::find(4);

        $discount_code = DiscountCode::findByValidCodeApplicationAndUser($code, $applies_to, $user);

        // if we don't have a valid discount code
        if (!$discount_code) {
            return response()->apiproblem(['valid' => false]);
        }

        return $this->baseTransformItem($discount_code, null, $this->transformer)->respond();
    }
}
