<?php

namespace App\Transformers\V1;

use App\Models\Test;
use League\Fractal\TransformerAbstract;

class TestTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Test $test)
    {
        return [
            'id' => $test->id,
            'results_key' => $test->tempResultsURL(),
            'sku_id' => $test->sku_id
        ];
    }
}
