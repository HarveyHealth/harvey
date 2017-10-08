<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Validation\Rule;

class DiscountCode extends Model
{
    const ALLOWED_APPLIES_TO = ['lab-test', 'consultation', 'all'];

    protected $dates = [
        'created_at',
        'deleted_at',
        'expires_at',
    ];

    public static function findByValidCodeApplicationAndUser($code, $applies_to, User $user)
    {
        $discount_code = DiscountCode::withCode($code)->enabled()->notExpired()->appliesTo($applies_to)->first();

        // if we don't have a valid code
        if (!$discount_code)
            return false;

        // if this is not a one-time use code
        // go head and return it
        if (!$discount_code->one_time_use)
            return $discount_code;

        // otherwise we need to do some checking
        if ($user->isNotPatient()) {
            ops_warning('A non-patient is trying to use a coupon code');
            return false;
        }

        $codes_used = Invoice::where('discount_code_id', $discount_code->id)->where('patient_id', $user->patient->id)->count();

        // they've already used this one
        if ($codes_used > 0)
            return false;

        return $discount_code;
    }

    public function discountForSubtotal($subtotal)
    {
        switch ($this->discount_type) {
            case 'percent':
                $amount = $subtotal * $this->amount/100;
                break;

            case 'dollars':
                $amount = $this->amount;
                break;
        }

        // no negative dollars
        if ($subtotal - $amount < 0) {
            $amount = $subtotal;
        }

        // discounts will always be in negative dollars
        return -$amount;
    }

    public function itemDescription()
    {
        $description = '';

        switch($this->discount_type) {
            case 'dollars':
                $description = 'Discount: $' . "{$this->amount} off";
                break;

            case 'percent':
                $description = "Discount: {$this->amount}% off";
                break;
        }

        return $description;
    }

    public function scopeWithCode(Builder $builder, $code)
    {
        return $builder->where('code', $code);
    }

    public function scopeEnabled(Builder $builder)
    {
        return $builder->where('enabled', true);
    }

    public function scopeNotExpired(Builder $builder)
    {
        return $builder->where(function (Builder $builder) {
            $builder->whereNull('expires_at')->orWhere('expires_at', '>', date('Y-m-d H:i:s'));
        });
    }

    public function scopeAppliesTo(Builder $builder, $applies_to)
    {
        return $builder->where(function (Builder $builder) use ($applies_to) {
            $builder->where('applies_to', $applies_to)->orWhere('applies_to', 'all');
        });
    }
}
