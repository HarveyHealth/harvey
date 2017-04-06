<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
            'terms' => true
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
}
