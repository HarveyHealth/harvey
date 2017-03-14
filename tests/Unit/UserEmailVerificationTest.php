<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserEmailVerificationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_the_user_model_generates_an_email_token_and_link()
    {
        $params = [
            'id' => 123456789,
            'email' => 'testeroni@goharvey.com',
            'created_at' => '2017-01-01 00:00:00'
        ];

        $sha_param = implode('|', $params);
        $token_value = sha1($sha_param);
        $link_value = secure_url("/verify/123456789/{$token_value}");

        $user = factory(User::class)->create($params);

        $this->assertEquals($token_value, $user->emailVerificationToken());
        $this->assertEquals($link_value, $user->emailVerificationURL());
    }
}
