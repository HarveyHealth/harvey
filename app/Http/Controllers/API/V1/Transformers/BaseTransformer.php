<?php

namespace App\Http\Controllers\API\V1;

class BaseTransformer extends TransformerAbstract
{
    public function transform($object) {
        return $object->toArray();
    }
}
