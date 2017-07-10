<?php

namespace Tests\Feature;

use App\Lib\PhoneNumberVerifier;
use App\Models\Patient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Carbon, Log, Redis;

class PhoneNumberVerifierTest extends TestCase
{
    use DatabaseMigrations;

    public function test_phone_verified_at_is_set_to_null_if_phone_is_changed()
    {
        $patient = factory(Patient::class)->create();

        $now = Carbon::now();
        $patient->user->phone_verified_at = $now;
        $patient->user->save();

        $this->assertDatabaseHas('users', [
            'phone' => $patient->user->phone,
            'phone_verified_at' => $now->toDateTimeString(),
        ]);

        $newPhone = $this->getRandomValidPhone();
        $patient->user->phone = $newPhone;
        $patient->user->save();

        $this->assertDatabaseHas('users', [
            'phone' => $newPhone,
            'phone_verified_at' => null,
        ]);
    }

    public function test_confirmation_sms_is_sent_after_phone_is_changed()
    {
        Log::spy();

        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        $user->phone = $this->getRandomValidPhone();
        $user->save();

        $code = Redis::get("phone_validation:{$user->id}:{$user->phone}");
        $message = "Your Harvey phone verification code is {$code}";

        Log::shouldHaveReceived('info')->with("Faking sending text message to {$user->phone} with message: {$message}")->once();
    }
}
