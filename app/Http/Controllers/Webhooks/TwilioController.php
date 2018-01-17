<?php

namespace App\Http\Controllers\Webhooks;

use Twilio\Twiml;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\{User,Appointment};
use ResponseCode;

use Lang, Bugsnag, Exception;

class TwilioController extends BaseWebhookController
{
    /**
     * Handles Customer Feedback
     */
    public function handle(Request $request){
        try {
            $from = $request->input('From');

            $response = strtolower(trim($request->input('Body')));

            // if the response is not valid, bail
            if (!in_array($response, [1, 2, 3, 4, 5])) {
                return $this->reply(Lang::get("sms.errors.input"));
            }

            // find this user
            $user = $this->getUserForPhone($from);

            // if the user isn't found
            if (!$user || !$user->enabled) {
                return $this->reply(Lang::get("sms.errors.user_not_found"));
            }

            // if they're not a patient, bail
            $patient = $user->patient;
            if (!$patient) {
                return $this->reply(Lang::get("sms.errors.user_not_found"));
            }

            // find the most recent job offer sent to them
            $appointment = Appointment::complete()->where('patient_id', $patient->id)->orderBy('id', 'DESC')->first();
            if (!$appointment) {
                return $this->reply(Lang::get("sms.errors.appointment_not_found"));
            }

            // stores feedback in the DB
            $appointment->doctor_rate = $response;
            $appointment->save();
            return $this->reply(Lang::get("sms.success.thanks_response"));

        } catch (Exception $e) {
            Bugsnag::notifyException($e); // log error
            return $this->reply(Lang::get("sms.errors.generic"));
        }
    }

    protected function getUserForPhone($phone)
    {
        //remove + prefix which comes w twilio
        return User::where("phone", "=", preg_replace("/^\+1/", "", $phone))->first();
    }

    protected function reply(string $message,int $status = ResponseCode::HTTP_OK):Response
    {
        $twiml = new Twiml();
        $twiml->message($message);

        return response($twiml, $status)->header("content-type", "text/xml");
    }
}
