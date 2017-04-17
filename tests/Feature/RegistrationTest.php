<?php

namespace Tests\Feature;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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
            'zip' => 91106
        ];
        
        // When a request is made to create a new user
        $response = $this->post(route('users.create'), $parameters);

        // It is successful
        $response->assertStatus(200);
        
        // The user object is shown
        $response->assertJsonFragment(['first_name' => 'John', 'last_name' => 'Smith']);
        
        // And a patient is created which belongs to the new user
        $created_user_id = $response->decodeResponseJson()["data"]["id"];
        $this->assertDatabaseHas('patients', ['user_id' => $created_user_id]);
    }
    
    public function test_it_does_not_create_a_user_if_the_zip_code_is_not_serviceable()
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
        $response = $this->post(route('users.create'), $parameters);
    
        // It returns a bad request response
        $response->assertStatus(400);
        
        // And an appropriate message will be displayed
        $response->assertJsonFragment(["detail" => "Sorry, we do not service this zip."]);
        
        // And no new user is created
        $this->assertDatabaseMissing('users', ['first_name' => 'Albus']);
    }
}
