<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_a_user_can_view_their_own_account_information()
    {
        // GIVEN a user
        $user = factory(User::class)->create();

        // WHEN they access the show user endpoint
        Passport::actingAs($user);
        $response = $this->json('GET', 'api/v1/users/' . $user->id);
    
        // THEN it is successful
        $response->assertStatus(200);
        
        // AND the user can see their own information
        $response->assertJsonFragment(['type' => 'users', 'id' => "{$user->id}"]);
    }
    
    public function test_a_user_can_modify_their_account_information()
    {
        // GIVEN a user
        $user = factory(User::class)->create();
        
        // AND user parameters
        $parameters = ['first_name' => 'ZZXXYY'];
        
        // WHEN the user attempts updating their account with parameters
        Passport::actingAs($user);
        $response = $this->json('PATCH', 'api/v1/users/' . $user->id, $parameters);
        
        // THEN it is successful
        $response->assertStatus(200);
        
        // AND we recognize a change has been made to the user
        $response->assertJsonFragment(['first_name' => 'ZZXXYY']);
    }
    
    public function test_a_user_cannot_modify_guarded_properties_of_their_account(){
        // GIVEN a user
        $user = factory(User::class)->create();
        
        // AND parameters with guarded property
        $parameters = ['enabled' => false];
    
        // WHEN the user attempts updating their account with a guarded property
        Passport::actingAs($user);
        $response = $this->json('PATCH', 'api/v1/users/' . $user->id, $parameters);
        
        // THEN is is successful
        $response->assertStatus(200);
        
        // BUT the property will remain unchanged
        $this->assertDatabaseHas('users', [
            'id' => "{$user->id}",
            'enabled' => true
        ]);
    }
    
    public function test_a_user_cannot_modify_another_users_account_information()
    {
        // GIVEN user A and user B exist
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        
        // WHEN user A tries to view user B data
        Passport::actingAs($userA);
        $response = $this->json('GET', 'api/v1/users/' . $userB->id);
        
        // THEN it is not successful
        $response->assertStatus(401);
        $response->assertSee('Unauthorized to view this resource');
    }
}
