<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InvoiceItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoiceItemPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param      $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        return $user->isAdmin() ?: null;
    }

    /**
     * @param User    $user
     * @param InvoiceItem $invoice_item
     * @return bool
     */
    public function view(User $user, InvoiceItem $invoice_item)
    {
        return $user->is($invoice_item->invoice->patient->user);
    }

    /**
     * Determine whether the user can create InvoiceItems.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * @param User    $user
     * @param InvoiceItem $invoice_item
     * @return bool
     */
    public function update(User $user, InvoiceItem $invoice_item)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the InvoiceItem.
     *
     * @param  \App\User  $user
     * @param  \App\InvoiceItem  $invoice_item
     * @return mixed
     */
    public function delete(User $user, InvoiceItem $invoice_item)
    {
        return false;
    }
}
