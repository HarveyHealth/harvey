<?php

use App\Lib\{TimeslotManager, ZipCodeValidator};
use App\Models\{Appointment, LabOrder, Practitioner, User};

Validator::extend('serviceable', function ($attribute, $value, $parameters, $validator) {
    return app()->make(ZipCodeValidator::class)->setZip($value)->isServiceable();
});

Validator::extendImplicit('required_if_is_admin', function ($attribute, $value, $parameters, $validator) {
    return !(currentUser()->isAdmin() && empty($value));
});

Validator::extendImplicit('required_if_is_practitioner', function ($attribute, $value, $parameters, $validator) {
    return !(currentUser()->isPractitioner() && empty($value));
});

Validator::extendImplicit('required_if_is_patient', function ($attribute, $value, $parameters, $validator) {
    return !(currentUser()->isPatient() && empty($value));
});

Validator::extend('practitioner_is_available', function ($attribute, $value, $parameters, $validator) {
    if (!empty($parameters)) {
        $appointmentEditing = Appointment::find($parameters[0]);
        $practitioner = Practitioner::find($appointmentEditing->practitioner->id);
    } elseif (currentUser()->isPractitioner()) {
        $practitioner =  currentUser()->practitioner;
    } elseif ($practitionerId = array_get($validator->attributes(), 'practitioner_id', false)) {
        $practitioner = Practitioner::find($practitionerId);
    } else {
        return false;
    }

    return $practitioner->availability()->canScheduleAt(Carbon::parse($value, 'UTC'), $appointmentEditing ?? null);
});

Validator::extend('order_was_not_shipped', function ($attribute, $value, $parameters, $validator) {
    if (empty($parameters)) {
        return false;
    }

    $labOrder = LabOrder::find($parameters[0]);

    return $labOrder->wasNotShipped();
});

Validator::extend('user_has_a_card', function ($attribute, $value, $parameters, $validator) {
    if (in_array($value, ['canceled', 'recommended'])) {
        return true;
    }

    if (empty($parameters)) {
        return false;
    }

    return User::findOrFail($parameters[0])->hasACard();
});
