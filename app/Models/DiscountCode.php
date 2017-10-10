<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    public static function findByValidCodeApplicationAndUser($code, $applies_to, $user)
    {
        $discount_code = DiscountCode::withCode($code)->enabled()->notExpired()->appliesTo($applies_to)->limit(1)->first();

        // if we don't have a valid code
        if (!$discount_code)
            return false;

        // make sure this is a patient
        if (!$user->isPatient()) {
            ops_warning('A non-patient is trying to use a coupon code');
            return false;
        }

        // if this is not a one-time use code
        // go ahead and return it
        if (!$discount_code->one_time_use)
            return $discount_code;

        // check to see if this person has used this discount code before
        $codes_used = \App\Models\Invoice::where('discount_code_id', $discount_code->id)
                        ->where('patient_id', $user->patient->id)->count();

        // if they've already used this one
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

    public function scopeWithCode($query, $code)
    {
        return $query->where('code', '=', $code);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('expires_at')->orWhere('expires_at', '>', date('Y-m-d H:i:s'));
        });
    }

    public function scopeAppliesTo($query, $applies_to)
    {
        return $query->where(function ($query) use ($applies_to) {
            $query->where('applies_to', $applies_to)->orWhere('applies_to', 'all');
        });
    }
}
