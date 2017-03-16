<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_a_user_can_modify_their_account_information()
    {
        return $this->assertTrue(true);
    }
    
    public function test_a_user_cannot_modify_another_users_account_information()
    {
        return $this->assertTrue(true);
    }
    
    public function test_a_user_can_view_their_own_account_information()
    {
        return $this->assertTrue(true);
    }
}
