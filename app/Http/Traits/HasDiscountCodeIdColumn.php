<?php

namespace App\Http\Traits;

use App\Models\{DiscountCode, User};

trait HasDiscountCodeIdColumn
{
    public function DiscountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function setDiscountCode(User $user, string $code = null, string $applies_to = 'all')
    {
        if (empty($discount_code = DiscountCode::findByValidCodeApplicationAndUser($code, $applies_to, $user))) {
            return false;
        }

        $this->discount_code_id = $discount_code->id;

        return $this->save();
    }
}
