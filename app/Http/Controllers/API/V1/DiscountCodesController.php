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
            'code' => 'required|exists:discount_codes,code',
            'applies_to' => 'required'
        ]);

        $code = $request->input('code');
        $applies_to = $request->input('applies_to');
        // $user = auth()->user();
        $user = \App\Models\User::findOrFail(1);

        $discount_code = DiscountCode::findByValidCodeApplicationAndUser($code, $applies_to, $user);

        if (!$discount_code) {
            return $this->respondWithError('Invalid Code', 'Invalid Code.', ResponseCode::HTTP_NOT_FOUND);
        }

        return $this->baseTransformBuilder($discount_code, null, $this->transformer)->respond();
    }
}
