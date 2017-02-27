<?php

namespace Tests\Feature;

use Mail;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisteredUserEmailVerificationTest extends TestCase
{
	use DatabaseMigrations;

    public function test_it_sends_an_email_to_the_user_after_creation()
    {
        $params = [
            'first_name' => 'John',
            'last_name' => 'Cena',
            'email' => 'jcena@goharvey.com',
            'phone' => '6261231234',
            'password' => 'secret',
            'terms' => true
        ];

        $response = $this->call('POST', 'register', $params);

        $response->assertRedirect(route('home'));
    }
}

