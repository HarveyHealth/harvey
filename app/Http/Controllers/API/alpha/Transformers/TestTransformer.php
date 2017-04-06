<?php

namespace App\Http\Controllers\API\alpha\Transformers;

class TestTransformer extends Transformer
{
    public function transform($test)
    {
        return [
            'id' => $test->id,
            'results_key' => $test->tempResultsURL(),
            'sku_id' => $test->sku_id
      ];
    }
}
