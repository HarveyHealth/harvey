<?php

use App\Lib\{TimeslotManager, ZipCodeValidator};
use App\Models\{Appointment, Practitioner, LabOrder};
use App\Models\DiscountCode;

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

Validator::extend('appointments_less_than',function($attribute, $value, $parameters, $validator){
    if (empty($parameters)) {
        return false;
    }

    // only allow search by patient or practitioner
    if (!in_array($attribute,['patient_id','practitioner_id'])){
        throw new InvalidArgumentException("Invalid value for Attribute argument");
    }

    // count pending appointments
    $count = Appointment::pending()->where($attribute,$value)->count();

    return $count < $parameters[0];
});
