<?php

namespace App\Http\Controllers\API\alpha;

class BaseTransformer extends TransformerAbstract
{
    public function transform($object) {
        return $object->toArray();
    }
}
