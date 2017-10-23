<?php

namespace App\Http\Controllers\Webhooks;

use Twilio\Twiml;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\{User,Appointment,AppointmentFeedback};
use ResponseCode;

use Bugsnag, Exception;

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
                \Log::info('Sorry please enter a number 1 to 5');
                return $this->reply('Sorry please enter a number 1 to 5', ResponseCode::HTTP_BAD_REQUEST);
            }

            // find this user
            $user = $this->getUserForPhone($from);

            // if the user isn't found
            if (!$user || !$user->enabled) {
                \Log::info("Sorry, we can't seem to find your phone number in our records. If you feel this is an error, please contact us at support@goharvey.com");
                return $this->reply("Sorry, we can't seem to find your phone number in our records. If you feel this is an error, please contact us at support@goharvey.com",
                        ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
            }

            // if they're not a patient, bail
            $patient = $user->patient;
            if (!$patient) {
                \Log::info("Sorry, you are not registered with us as a customer. If you feel this is an error, please contact us at support@goharvey.com");
                return $this->reply("Sorry, you are not registered with us as a customer. If you feel this is an error, please contact us at support@goharvey.com",
                        ResponseCode::HTTP_BAD_REQUEST);
            }

            // find the most recent job offer sent to them
            $appointment = Appointment::complete()->where('patient_id', $patient->id)->orderBy('id', 'DESC')->first();
            if (!$appointment) {
                \Log::info("Sorry, we cab seem to find your latest Consultation. Please contact us at support@goharvey.com");
                return $this->reply("Sorry, we cab seem to find your latest Consultation. Please contact us at support@goharvey.com",
                        ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
            }

            // stores feedback in the DB
            $feedback = new AppointmentFeedback();
            $feedback->appointment_id = $appointment->id;
            $feedback->doctor_rate = $response;
            $feedback->save();


            return $this->reply("Thank you for your response!");

        } catch (Exception $e) {
            //Bugsnag::notifyException($e); // log error
            \Log::info($e->getMessage());
            return $this->reply("We're sorry. There was an error. Please try again later.");
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
