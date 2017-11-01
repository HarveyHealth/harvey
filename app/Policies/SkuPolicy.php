<?php

namespace App\Policies;

use App\Models\User;
use App\Sku;
use Illuminate\Auth\Access\HandlesAuthorization;

class SkuPolicy
{
    use HandlesAuthorization;
    
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the sku.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Sku  $sku
     * @return mixed
     */
    public function view(User $user, Sku $sku)
    {
        return true;
    }

    /**
     * Determine whether the user can create skus.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the sku.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Sku  $sku
     * @return mixed
     */
    public function update(User $user, Sku $sku)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the sku.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Sku  $sku
     * @return mixed
     */
    public function delete(User $user, Sku $sku)
    {
        return $user->isAdmin();
    }
}
