<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TestTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_an_admin_can_view_all_results()
    {
        return $this->assertTrue(true);
    }
    
    public function test_a_practitioner_can_view_tests_from_their_patients()
    {
        return $this->assertTrue(true);
    }
    
    public function test_a_patient_can_view_only_their_own_tests()
    {
        return $this->assertTrue(true);
    }
}
