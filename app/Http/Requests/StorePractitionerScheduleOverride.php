<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\StrictValidatorException;

class StorePractitionerScheduleOverride extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isPractitioner() || $this->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'date' => 'required|date',
            'start_time' => "required|string",
            'stop_time' => "required|string",
            'notes' => 'nullable|string|max:191',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->start_time >= $this->stop_time) {
                $validator->errors()->add('stop_time', 'The first block must start before the last block.');
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new StrictValidatorException($validator->errors()->first());
    }
}
