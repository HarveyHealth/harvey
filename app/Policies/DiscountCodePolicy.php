<?php

namespace App\Policies;

use App\Models\{User, DiscountCode};
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountCodePolicy
{
    use HandlesAuthorization;

    public function view(User $user, DiscountCode $discount_code)
    {
        return true;
    }
}
