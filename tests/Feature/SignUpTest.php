<?php

namespace Tests\Feature;

use App\Models\Patient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use ResponseCode;

class SignUpTest extends TestCase
{
    use DatabaseMigrations;

    public function test_if_get_started_returns_ok()
    {
        $response = $this->get('/get-started');
        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    public function test_if_signup_redirect_to_dashboard_if_logged_in()
    {
        Passport::actingAs(factory(Patient::class)->create()->user);
        $response = $this->get('/signup');

        $response->isRedirect();
    }
}
