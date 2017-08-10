<?php

namespace App\Http\Controllers\Webhooks;

use Illuminate\Http\Request;
use App\Lib\Slack;
use App\Notifications\SlackNotification;
use App\Models\User;
use Carbon\Carbon;
use ResponseCode, Log;

class TypeformController extends BaseWebhookController
{
    public function handle()
    {
        $eventType = request('event_type');
        $formResponse = request('form_response');

        if ('form_response' != $eventType) {
            ops_warning('TypeformController', "Unhandled Typeform event: '{$eventType}'");
        } elseif (empty($userId = $formResponse['hidden']['harveyid'])) {
            ops_warning('TypeformController', "Missing 'harveyid' value when handling event '{$eventType}'.");
        } elseif (empty($user = User::find($userId))) {
            ops_warning('TypeformController', "Can't find User #{$userId} when handling event '{$eventType}'.");
        } else {
            if (empty($user->intake_completed_at)
                && !empty($formResponse['definition']['fields'])
                && !empty($formResponse['answers'])
                && count($formResponse['definition']['fields']) == count($formResponse['answers'])) {
                    $user->intake_completed_at = Carbon::now();
                    $user->save();
            }
            return response("Thanks!", ResponseCode::HTTP_OK);
        }

        return response("Can't process webhook.", ResponseCode::HTTP_BAD_REQUEST);
    }
}
