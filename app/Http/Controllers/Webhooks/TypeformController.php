<?php

namespace App\Http\Controllers\Webhooks;

use App\Lib\TimeInterval;
use App\Models\User;
use Cache, Carbon, ResponseCode;

class TypeformController extends BaseWebhookController
{
    public function handle()
    {
        $eventType = request('event_type') ?? 'Unknown';
        $formResponse = request('form_response');

        if ('form_response' != $eventType) {
            ops_warning('TypeformController', "Unhandled Typeform event: '{$eventType}'");
        } elseif (empty($userId = $formResponse['hidden']['harvey_id'])) {
            ops_warning('TypeformController', "Missing 'harvey_id' value when handling event '{$eventType}'.");
        } elseif (empty($formResponse['hidden']['intake_validation_token'])) {
            ops_warning('TypeformController', "Missing 'intake_validation_token' value when handling event '{$eventType}' for (supposedly) User ID #{$userId}.");
        } elseif (empty($user = User::find($userId))) {
            ops_warning('TypeformController', "Can't find User #{$userId} when handling event '{$eventType}'.");
        } elseif ($formResponse['hidden']['intake_validation_token'] != $user->patient->intake_validation_token) {
            ops_warning('TypeformController', "Invalid intake_validation_token '{$formResponse['hidden']['intake_validation_token']}'for User #{$userId} when handling event '{$eventType}'.");
        } else {
            $user->patient->intake_token = $formResponse['token'];
            $user->patient->save();

            Cache::forget("intake-token-{$formResponse['token']}-data");
        }

        return response("Thanks!", ResponseCode::HTTP_OK);
    }
}
