<?php

namespace App\Lib\Validation;

use App\Exceptions\StrictValidatorException;
use Illuminate\Support\Facades\Validator;

class StrictValidator extends Validator
{
    public static function check(array $data, array $rules, array $messages = [], array $customAttributes = [], $update = false)
    {
        $validator = parent::make($data, $rules, $messages, $customAttributes);

        if ($update) {
            $validator->after(function ($validator) {
                foreach ($validator->getData() as $key => $value) {
                    if (is_array($value)){
                        if (empty(preg_grep("/{$key}\..*/", array_keys($validator->getRules())))){
                            $validator->errors()->add($key, "{$key} is not allowed to be updated.");
                        }
                    }
                    else{
                        if ('_method' != $key && !in_array($key, array_keys($validator->getRules()))) {
                            $validator->errors()->add($key, "{$key} is not allowed to be updated.");
                        }
                    }
                }
            });
        }

        if ($validator->fails()) {
            throw new StrictValidatorException($validator->errors()->first());
            return false;
        }

        return true;
    }

    public static function checkUpdate(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        return static::check($data, $rules, $messages, $customAttributes, true);
    }

}
