<?php

Validator::extendImplicit('required_if_is_admin', function ($attribute, $value, $parameters, $validator) {
    return !(auth()->user()->isAdmin() && empty($value));
});