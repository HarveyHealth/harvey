<?php

namespace Tests\Feature;

use App\Lib\PhoneNumberVerifier;
use App\Models\{Patient, User};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon, Log, ResponseCode;
use Illuminate\Support\Facades\Redis;

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

    public function test_phone_verification_sms_is_sent_after_phone_is_changed()
    {
        Log::spy();

        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        $user->phone = $this->getRandomValidPhone();
        $user->save();

        $code = Redis::get("phone_validation:{$user->id}:{$user->phone}");

        $this->assertTextWasSent($user->phone, "Your Harvey phone verification code is {$code}");
    }

    public function test_a_five_digit_validation_code_is_requested()
    {
        $user = factory(User::class)->create([
            'phone_verified_at' => null,
        ]);

        $code = 1234;

        Passport::actingAs($user);
        $response = $this->json('GET', "api/v1/users/{$user->id}/phone/verify?code={$code}");

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);
        $response->assertSee('Bad Request.');
    }

    public function test_phone_verified_at_is_set_after_code_submit()
    {
        $user = factory(User::class)->create([
            'phone_verified_at' => null,
        ]);

        $this->assertNull($user->fresh()->phone_verified_at);

        $code = Redis::get("phone_validation:{$user->id}:{$user->phone}");

        Passport::actingAs($user);
        $response = $this->json('GET', "api/v1/users/{$user->id}/phone/verify?code={$code}");

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJsonFragment(['verified' => true]);
        $this->assertNotNull($user->fresh()->phone_verified_at);
    }

    public function test_phone_verified_at_is_not_set_after_wrong_code_submit()
    {
        $user = factory(User::class)->create([
            'phone_verified_at' => null,
        ]);

        $code = 12345;

        Passport::actingAs($user);
        $response = $this->json('GET', "api/v1/users/{$user->id}/phone/verify?code={$code}");

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJsonFragment(['verified' => false]);
        $this->assertNull($user->fresh()->phone_verified_at);
    }

    public function test_phone_verification_sms_is_sent_when_requested()
    {
        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        Log::spy();

        Passport::actingAs($user);
        $response = $this->json('POST', "api/v1/users/{$user->id}/phone/sendverificationcode");

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJsonFragment(['status' => 'Verification code sent.']);

        $code = Redis::get("phone_validation:{$user->id}:{$user->phone}");

        $this->assertTextWasSent($user->phone, "Your Harvey phone verification code is {$code}");
    }

    public function test_phone_verification_sms_is_not_sent_if_other_user_requested_it()
    {
        factory(Patient::class)->create();
        $patient = factory(Patient::class)->create();
        $user = $patient->user;

        Log::spy();

        Passport::actingAs($user);
        $response = $this->json('POST', "api/v1/users/1/phone/sendverificationcode");

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN);
        $response->assertJsonFragment(['status' => 'Verification code not sent.']);

        Log::shouldNotHaveReceived('info');
    }
}
