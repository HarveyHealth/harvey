<?php

namespace App\Http\Controllers\API\V1;

use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    public function transform($object) {
        return $object->toArray();
    }
}
