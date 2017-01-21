<?php

namespace App\Http\Controllers\API\alpha\Transformers;

/**
 * An abstract class used to transform results of
 * single entities or collections into API output.
 *
 * The main purpose of Transformers is to provide an Interface
 * for our data. Should column names change in the future,
 * we simply modify the transformer once.
 */
abstract class Transformer
{

    public function transformCollection($items)
    {
        $result = [];

        foreach ($items as $item) {
            $result[] = $this->transform($item);
        }

        return $result;
    }

    abstract public function transform($item);
}
