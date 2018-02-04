<?php

namespace App\Http\Controllers\Webhooks;

use App\Lib\Clients\Typeform;
use App\Lib\TimeInterval;
use App\Models\{Intake, User};
use Cache, Carbon, ResponseCode;

class TypeformController extends BaseWebhookController
{
    public function handle()
    {
        $eventType = request('event_type') ?? 'Unknown';
        $formResponse = request('form_response');

        if ('form_response' != $eventType) {
            ops_warning('Typeform webhook', "Unhandled Typeform event: '{$eventType}'");
        } elseif (empty($userId = $formResponse['hidden']['harvey_id'])) {
            ops_warning('Typeform webhook', "Missing 'harvey_id' value when handling event '{$eventType}'.");
        } elseif (empty($formResponse['hidden']['intake_validation_token'])) {
            ops_warning('Typeform webhook', "Missing 'intake_validation_token' value when handling event '{$eventType}' for (supposedly) User ID #{$userId}.");
        } elseif (empty($user = User::find($userId))) {
            ops_warning('Typeform webhook', "Can't find User #{$userId} when handling event '{$eventType}'.");
        } elseif ($user->isNotPatient()) {
            ops_warning('Typeform webhook', "User #{$userId} is not Patient, can't handle '{$eventType}' event.");
        } elseif ($formResponse['hidden']['intake_validation_token'] != $user->patient->intake_validation_token) {
            ops_warning('Typeform webhook', "Invalid intake_validation_token '{$formResponse['hidden']['intake_validation_token']}'for User #{$userId} when handling event '{$eventType}'.");
        } elseif (Intake::whereToken($form_token = $formResponse['token'])->first()) {
            ops_warning('Typeform webhook', "Form ID #{$form_token} User #{$userId} when handling event '{$eventType}'.");
        } else {
            $data = Typeform::getDataForToken($form_token);
            $user->intake()->create(['data' => $data, 'token' => $form_token]);

            if ($user->isPatient()) {
                $answers = $data['responses'][0]['answers'];

                $dob_question_id = 'date_55410467';
                $user->patient->birthdate = Carbon::createFromFormat('Y-m-d', $answers[$dob_question_id]);

                $address_question_id = 'textfield_55452067';
                $user->address_1 = str_limit($answers[$address_question_id], 96);

                $user->push();
            }

            ops_info('Typeform webhook', "User #{$userId} has submitted the Intake form.", 'practitioners');
        }

        return response("Thanks!", ResponseCode::HTTP_OK);
    }
}
