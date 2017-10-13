<?php

namespace App\Http\Controllers\API\V1;

use App\Lib\Validation\StrictValidator;
use App\Models\DiscountCode;
use App\Transformers\V1\DiscountCodeTransformer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use ResponseCode;

class DiscountCodesController extends BaseAPIController
{
    protected $resource_name = 'discountcode';

    /**
     * DiscountCodesController constructor.
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
            'discount_code' => 'required|max:24',
            'applies_to' => ['required', Rule::in(DiscountCode::ALLOWED_APPLIES_TO)],
        ]);

        $code = $request->input('discount_code');
        $applies_to = $request->input('applies_to');

        $discount_code = DiscountCode::findByValidCodeApplicationAndUser($code, $applies_to, currentUser());

        if (!$discount_code) {
            return response()->apiproblem(['valid' => false], ResponseCode::HTTP_OK);
        }

        return $this->baseTransformItem($discount_code)->respond();
    }
}
