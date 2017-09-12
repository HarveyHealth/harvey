<?php

namespace App\Http\Traits;

trait IsNot
{
    public function __call($method, $args)
    {
        if (starts_with($method, 'isNot') && method_exists($this, $isMethod = 'is'.substr($method, 5))) {
            return !$this->$isMethod();
        }

        return parent::__call($method, $args);
    }
}
