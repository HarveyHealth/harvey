<?php

/*
|--------------------------------------------------------------------------
| Webhook Routes
|--------------------------------------------------------------------------
|
*/

Route::post('stripe', 'Webhooks\StripeController@handle');
Route::any('intakeq', 'Webhooks\IntakeQController@handle');
Route::match(['post', 'put'], 'typeform', 'Webhooks\TypeformController@handle');
Route::post('twilio', 'Webhooks\TwilioController@handle');
Route::post('shippo', 'Webhooks\ShippoController@handle');
Route::match(['post', 'put'], 'shopify', 'Webhooks\ShopifyController@handle');
