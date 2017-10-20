<?php

namespace App\Http\Controllers\Webhooks;

use Illuminate\Http\Request;
use App\Lib\Slack;
use App\Notifications\SlackNotification;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class IntakeQController extends BaseWebhookController
{
    protected $intakeq;
    protected $geocoder;

    public function __construct(\App\Lib\Clients\IntakeQ $intakeq, \App\Lib\Clients\Geocoder $geocoder)
    {
        $this->intakeq = $intakeq;
        $this->geocoder = $geocoder;
    }

    public function handle()
    {
        $payload = request()->all();

        \Log::info($payload);

        $type = $payload['Type'];
        $intake_id = $payload['IntakeId'];

        if ($type == 'Intake Submitted') {
            $this->createUserForIntakeID($intake_id);
        } else {
            $message = '*[Notice]* Unhandled IntakeQ Type: ' . $type;

            // log it
            \Log::info($message);

            // slack it
            (new Slack)->notify(new SlackNotification($message, 'engineering'));
        }

        return 'A-OK!';
    }

    public function createUserForIntakeID($intake_id)
    {
        $details = $this->getUserDetailsUsingIntakeID($intake_id);

        if (!$details) {
            $message = 'Could not create user for IntakeQ ID:  ' . $intake_id;

            // log it
            \Log::warning($message);

            // slack it
            (new Slack)->notify(new SlackNotification('*[Warning]* ' . $message, 'engineering'));

            throw new Exception($message);
        }

        if (isset($details['Date of Birth'])) {
            $birthdate = new Carbon($details['Date of Birth']);
            $birthdate = $birthdate->toDateString();
        }


        $data = [
            'first_name' => $details['First Name'] ?? null,
            'last_name' => $details['Last Name'] ?? null,
            'email' => $details['Email'] ?? null,
            'phone' => phone_for_db($details['Phone']) ?? null,
            'address_1' => $details['Street Address'] ?? null,
            'city' => $details['City'] ?? null,
            'state' => $details['State'] ?? null,
            'zip' => $details['Zip'] ?? null,
            'birthdate' => $birthdate ?? null,
            'user_type' => 'patient',
            'api_token' => str_random(60),
        ];

        request()->merge($data);

        $this->validate(request(), [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'required|email|max:150|unique:users',
            'phone' => 'required|phone:AUTO,US|max:13|unique:users',
        ]);

        try {
            $query = ($details['Street Address'] ?? '') . ', ' . $details['Zip'];
            $query = trim($query, ','); // get rid of extra commas
            $geodata = $this->geocoder->geocode($query);
        } catch (Exception $e) {
            // log it
            \Log::warning($e->getMessage());
        }

        // add geo data
        $data['longitude'] = $geodata['longitude'] ?? null;
        $data['latitude'] = $geodata['latitude'] ?? null;

        User::unguard();
        return User::create($data);
        User::guard();
    }

    public function getUserDetailsUsingIntakeID($intake_id)
    {
        $response = $this->intakeq->getIntake($intake_id);

        if ($response->getStatusCode() != 200) {
            $message = 'Could not retrieve IntakeQ information for ID:  ' . $intake_id;

            // log it
            \Log::warning($message);

            // slack it
            (new Slack)->notify(new SlackNotification('*[Warning]* ' . $message, 'engineering'));

            throw new Exception($message);
        }

        $json = json_decode($response->getBody());

        $fields = $this->requiredFields();

        $data = [];

        $questions = $json->Questions;

        foreach ($questions as $question) {
            if (in_array($question->Text, $fields)) {
                $data[$question->Text] = $question->Answer;
            }
        }

        return $data;
    }

    private function requiredFields()
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Street Address',
            'City',
            'State',
            'Zip',
            'Date of Birth',
            'Gender'
        ];
    }
}
