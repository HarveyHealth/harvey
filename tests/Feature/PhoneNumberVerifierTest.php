<?php

namespace Tests\Feature;

use App\Models\{Admin, Message, Patient, Practitioner};
use App\Events\MessageCreated;
use Illuminate\Support\Facades\{Event, Notification};
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon, ResponseCode;

class PhoneNumberVerifierTest extends TestCase
{
    use DatabaseMigrations;

    public function test_phone_verified_at_is_set_to_null_if_phone_is_changed()
    {
        $patient = factory(Patient::class)->create();
        $now = Carbon::now();

        $this->assertDatabaseHas('users', [
            'phone' => $patient->user->phone,
            'phone_verified_at' => $now->toDateTimeString(),
        ]);

        $newPhone = 555123123;
        $patient->user->phone = $newPhone;
        $patient->user->save();

        $this->assertDatabaseHas('users', [
            'phone' => $newPhone,
            'phone_verified_at' => null,
        ]);
    }
}
