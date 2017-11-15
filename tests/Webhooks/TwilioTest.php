<?php
namespace Tests\Webhooks;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Appointment;
use Faker\Factory as Faker;
use ResponseCode;


class TwilioTest extends TestCase{
    use DatabaseMigrations;

    public function test_customer_feedback_ok(){
        // create appointment
        $appointment = factory(Appointment::class)->create();

        // complete appointment
        $appointment->status_id = Appointment::COMPLETE_STATUS_ID;
        $appointment->save();

        // build Twilio request body
        $body = [
            'From' => '+1'.$appointment->patient->user->phone,
            'Body' => '5',
        ];


        // make call
        $response = $this->call('POST', 'webhook/twilio?key=' . config('webhook.key'),$body);

        // make sure response code is OK
        $response->assertStatus(ResponseCode::HTTP_OK);

        // reload the appointment from db
        $feedback = $appointment->fresh();
        // assert right values
        $this->assertEquals($feedback->doctor_rate, $body['Body']);
    }
}
