<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    public static function withValidCode($code)
    {
    	return DiscountCode::withCode($code)->enabled()->notExpired()->first();
    }

    public function discountForSubtotal($subtotal)
    {
    	switch($this->discount_type) {
    		case 'percent':
    			$amount = $subtotal * $this->amount/100;
    			break;

    		case 'dollars':
    			$amount = $this->amount;
    			break;
    	}

        // no negative dollars
        if ($subtotal - $amount < 0)
            $amount = $subtotal;

    	return $amount;
    }

    public function scopeWithCode($query, $code)
    {
    	return $query->where('code', $code);
    }

    public function scopeEnabled($query)
    {
    	return $query->where('enabled', true);
    }

    public function scopeNotExpired($query)
    {
    	return $query->whereNull('expires_at')->orWhere('expires_at','>',date('Y-m-d H:i:s'));
    }
}
