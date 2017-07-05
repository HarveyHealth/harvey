<?php

namespace Tests\Feature;

use App\Models\{Appointment, Practitioner, User};
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Auth, Mockery, ResponseCode;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    public function test_user_is_redirected_after_logout()
    {
        $response = $this->get('/logout');

        $this->assertTrue($response->isRedirect());
    }

    public function test_user_can_verify_his_email()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
            'password' => null,
        ]);
        $this->assertNull($user->email_verified_at);

        $response = $this->json('GET', $user->emailVerificationURL());
        $response->assertStatus(ResponseCode::HTTP_OK);

        $faker = Faker::create();

        $parameters = [
            'password' => $faker->password,
        ];

        $response = $this->json('POST', $user->emailVerificationURL(), $parameters);
        $this->assertTrue($response->isRedirect());

        $user = $user->fresh();

        $this->assertNotNull($user->email_verified_at);

        $newCredentials = ['email' => $user->email, 'password' => $parameters['password']];
        $this->assertTrue(auth()->validate($newCredentials));
    }


    public function test_wrong_verification_token_returns_404()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null,
            'password' => null,
        ]);
        $this->assertNull($user->email_verified_at);

        $response = $this->json('GET', $user->emailVerificationURL() . 'invalid');
        $response->assertStatus(ResponseCode::HTTP_NOT_FOUND);

        $response = $this->json('POST', $user->emailVerificationURL() . 'invalid');
        $response->assertStatus(ResponseCode::HTTP_NOT_FOUND);
    }

    public function test_user_is_redirected_to_appointments_if_password_already_set_and_no_upcoming_appointments()
    {
        $user = factory(User::class)->create();

        $response = $this->json('GET', $user->emailVerificationURL());
        $this->assertTrue($response->isRedirect());
        $this->assertStringEndsWith('dashboard#/appointments', $response->getTargetUrl());
    }

    public function test_user_is_redirected_to_dashboard_if_password_already_set_and_has_appointments()
    {
        $appointment = factory(Appointment::class)->create();

        $response = $this->json('GET', $appointment->patient->user->emailVerificationURL());
        $this->assertTrue($response->isRedirect());
        $this->assertStringEndsWith('dashboard', $response->getTargetUrl());
    }

    public function test_password_reset_page_returns_200()
    {
        $response = $this->json('GET', 'password/reset/some_token');
        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    public function test_password_a_user_can_ask_for_a_reset_password_email()
    {
        $user = factory(User::class)->create();

        $parameters = ['email' => $user->email];

        $response = $this->json('POST', 'password/email', $parameters);
        $response->assertStatus(ResponseCode::HTTP_FOUND);
        $this->assertEquals($response->getSession()->get('status'), 'We have e-mailed your password reset link!');
    }

    public function test_login_redirects_to_dashboard_if_logged_in()
    {
        Passport::actingAs(factory(Practitioner::class)->create()->user);

        $response = $this->get('/login');

        $this->assertTrue($response->isRedirect());
        $this->assertStringEndsWith('dashboard', $response->getTargetUrl());
    }

}
