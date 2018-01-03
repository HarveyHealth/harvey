<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
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
     * @param Invoice $invoice
     * @return bool
     */
    public function view(User $user, Invoice $invoice)
    {
        return $user->is($invoice->patient->user);
    }

    /**
     * Determine whether the user can create Invoices.
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
     * @param Invoice $invoice
     * @return bool
     */
    public function update(User $user, Invoice $invoice)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Invoice.
     *
     * @param  \App\User  $user
     * @param  \App\Invoice  $invoice
     * @return mixed
     */
    public function delete(User $user, Invoice $invoice)
    {
        return false;
    }
}
