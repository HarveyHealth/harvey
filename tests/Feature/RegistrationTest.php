<?php

namespace Tests\Feature;

use App\Events\UserRegistered;
use App\Models\{License, User};
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use ResponseCode;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_new_user_can_be_created()
    {
        // Given valid user data
        $parameters = [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'jsmith@yahoo.com',
            'password' => 'password',
            'terms' => true,
            'zip' => 91106,
        ];

        factory(License::class)->create(['state' => 'CA']);

        // When a request is made to create a new user
        $response = $this->post(route('users.store'), $parameters);

        // It is successful
        $response->assertStatus(ResponseCode::HTTP_CREATED);

        // The user object is shown
        $response->assertJsonFragment(['first_name' => 'John', 'last_name' => 'Smith']);

        // And a patient is created which belongs to the new user
        $created_user_id = $response->decodeResponseJson()["data"]["id"];
        $this->assertDatabaseHas('patients', ['user_id' => $created_user_id]);
    }

    public function test_it_does_not_create_a_user_if_the_zip_code_is_not_serviceable_but_it_does_create_a_lead()
    {
        // Given invalid user data
        $parameters = [
            'first_name' => 'Albus',
            'last_name' => 'Dumbledore',
            'email' => 'headmaster@hogwarts.co.uk',
            'password' => 'password',
            'terms' => true,
            'zip' => 11111
        ];

        // When a request is made to create a new user
        $response = $this->post(route('users.store'), $parameters);

        // It returns a bad request response
        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);

        // And an appropriate message will be displayed
        $response->assertJsonFragment([
            "detail" => [
                'message' => 'Sorry, we do not service this zip.',
                'city' => null,
                'state' => null,
            ]
        ]);

        // And no new user is created
        $this->assertDatabaseMissing('users', ['first_name' => 'Albus']);

        // But a lead is created
        $this->assertDatabaseHas('leads', ['email' => 'headmaster@hogwarts.co.uk', 'zip' => 11111]);
    }

    public function test_it_includes_state_and_zip_on_error_response_when_zip_code_is_not_serviceable()
    {
        // Given invalid user data
        $parameters = [
            'first_name' => 'Albus',
            'last_name' => 'Dumbledore',
            'email' => 'headmaster@hogwarts.co.uk',
            'password' => 'password',
            'terms' => true,
            'zip' => 10029
        ];

        $response = $this->post(route('users.store'), $parameters);

        $response->assertStatus(ResponseCode::HTTP_BAD_REQUEST);

        $response->assertJsonFragment([
            "detail" => [
                'message' => 'Sorry, we do not service this zip.',
                'city' => 'New York',
                'state' => 'NY',
            ]
        ]);
    }
}
