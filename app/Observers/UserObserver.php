<?php

namespace App\Observers;

use App\Events\UserUpdated;
use App\Events\PhoneNumberChanged;
use App\Models\User;

class UserObserver
{
    public function updating(User $user)
    {
        if ($user->isDirty('phone')) {
            $user->phone_verified_at = null;
        }

        $dirty_attributes = array_keys($user->getDirty());
        $address_attributes_updated = array_intersect(User::ADDRESS_ATTRIBUTES, $dirty_attributes);

        if ($user->isPatient() && !empty($address_attributes_updated)) {
            $user->patient->labOrders()->recommendedOrConfirmed()->each(function ($labOrder) use ($user) {
                foreach (User::ADDRESS_ATTRIBUTES as $attribute) {
                    $labOrder->$attribute = $user->$attribute;
                }
                $labOrder->save();
            });
         }
     }

    public function updated(User $user)
    {
        event(new UserUpdated($user));
    }

    public function saved(User $user)
    {
        // the phone is being updated
        if ($user->isDirty('phone') && !empty($user->phone)) {
                event(new PhoneNumberChanged($user, $user->getOriginal('phone')));
        }
    }
}
