<?php

Validator::extend('serviceable', function ($attribute, $value, $parameters, $validator) {
    return app()->make(\App\Lib\ZipCodeValidator::class)->setZip($value)->isServiceable();
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

Validator::extendImplicit('practitioner_is_available', function ($attribute, $value, $parameters, $validator) {
    if (currentUser()->isPractitioner()) {
        $practitioner =  currentUser()->practitioner;
    } elseif ($practitionerId = \Illuminate\Support\Arr::get($validator->attributes(), 'practitioner_id', false)) {
        $practitioner = \App\Models\Practitioner::find($practitionerId);
    } else {
        return false;
    }

    $appointmentAt = \Carbon::parse($value);
    $tsm = new \App\Lib\TimeslotManager;
    $appointmentAtTimeslot = $tsm->timeslotForDayAndTime($appointmentAt->format('l'), $appointmentAt->format('H:i'));

    return in_array($appointmentAtTimeslot, $practitioner->availability()->validAvailabilitySlotsForWeek($appointmentAt));
});
