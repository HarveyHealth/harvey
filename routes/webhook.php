<?php

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
|
*/

Route::post('stripe', 'Webhooks\StripeController@handle');
Route::any('intakeq', 'Webhooks\IntakeQController@handle');
