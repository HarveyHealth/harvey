<?php

use App\Lib\{TimeslotManager, ZipCodeValidator};
use App\Models\{Appointment, Practitioner, LabOrder, User};

Validator::extend('serviceable', function ($attribute, $value, $parameters, $validator) {
    return app()->make(ZipCodeValidator::class)->setZip($value)->isServiceable();
});

Validator::extend('user_setting', function ($attribute, $value, $parameters, $validator) {
    return array_key_exists(str_replace('settings.','', $attribute), User::DEFAULT_SETTINGS) and is_bool($value);
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

Validator::extend('admin_or_practitioner', function ($attribute, $value, $parameters, $validator) {
    return currentUser() && currentUser()->isAdminOrPractitioner();
});

Validator::extend('practitioner_is_available', function ($attribute, $value, $parameters, $validator) {
    if (!empty($parameters)) {
        $appointmentEditing = Appointment::find($parameters[0]);
        $practitioner = Practitioner::find($appointmentEditing->practitioner->id);
    } elseif (currentUser() && currentUser()->isPractitioner()) {
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

Validator::extend('appointments_less_than',function($attribute, $value, $parameters, $validator) {
    if (empty($parameters)) {
        return false;
    }

    if (!in_array($attribute, ['patient_id', 'practitioner_id'])) {
        throw new InvalidArgumentException("Can't search for an Appointment looking by: {$attribute}");
    }

    return Appointment::pending()->where($attribute, $value)->limit($parameters[0])->count() != $parameters[0];
});
