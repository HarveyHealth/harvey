<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SchedulerTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_it_only_allows_a_logged_in_user_to_view_the_scheduling_flow()
    {
        // Given no user
        // If we try to view the scheduler
        $response = $this->get(route('scheduler'));
        
        // Then the user will be redirected to the login page
        $response->assertRedirect(route('login'));
        
        // But if a new user is created
        $user = factory(User::class)->create();
    
        // And they try to view the scheduling flow
        $response = $this->actingAs($user)->get(route('scheduler'));
        
        // Then it will be successful
        $response->assertStatus(200);
    }
}
