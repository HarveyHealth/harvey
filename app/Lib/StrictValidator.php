<?php

namespace App\Lib;

use Illuminate\Support\Facades\Validator;

class StrictValidator extends Validator
{
    public static function make(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = parent::make($data, $rules, $messages, $customAttributes);

        $validator->after(function ($validator) {
            foreach ($validator->getData() as $key => $value) {
                if ('_method' != $key && !in_array($key, array_keys($validator->getRules()))) {
                    $validator->errors()->add($key, "{$key} is not allowed to be updated.");
                }
            }
        });

        return $validator;
    }
}
