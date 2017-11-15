<?php

namespace App\Policies;

use App\Models\{SoapNote, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class SoapNotePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return $user->isAdmin() ?: null;
    }

    public function get(User $user, SoapNote $soapNote)
    {
        return $user->isPractitioner() || $user->is($soapNote->creator) || $user->is($soapNote->patient->user);
    }

    public function update(User $user, SoapNote $soapNote)
    {
        return $user->isPractitioner() || $user->is($soapNote->creator) || $user->is($soapNote->patient->user);
    }

    public function delete(User $user, SoapNote $soapNote)
    {
        return $user->isPractitioner() || $user->is($soapNote->creator);
    }
}
