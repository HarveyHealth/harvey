<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterUserTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function testItRegistersANewUser()
    {
        $data = [
            'first_name' => 'First',
            'last_name' => 'Name',
            'email' => 'somerandomemail@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];

        $this->post('register', $data);

        $this->seeInDatabase('users', [
            'email' => 'somerandomemail@example.com'
        ]);
    }
}
