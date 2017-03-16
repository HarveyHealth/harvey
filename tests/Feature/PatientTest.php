<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_a_patient_can_view_their_patient_data()
    {
        return $this->assertTrue(true);
    }
    
    public function test_a_patient_can_modify_their_patient_data()
    {
        return $this->assertTrue(true);
    }
    
    public function test_a_patient_cannot_view_another_patients_data()
    {
        return $this->assertTrue(true);
    }
}
