<?php

namespace Tests\Unit;

use App\Models\Patient;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_a_patient_can_view_their_patient_data()
    {
        // GIVEN a patient
        $patient = factory(Patient::class)->create(['birthdate' => '1950-09-30']);
        
        // WHEN the patient attempts viewing their patient data
        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/patients/' . $patient->id);

        // THEN it is successful
        $response->assertStatus(200);
        
        // AND they see their birthdate
        $response->assertJsonFragment(['birthdate' => '1950-09-30']);
    }
    
    public function test_a_patient_can_modify_their_patient_data()
    {
        // GIVEN a patient
        $patient = factory(Patient::class)->create(['birthdate' => '1950-09-30']);
        
        // AND a patient's data parameter
        $parameters = ['birthdate' => '1999-01-01'];
        
        // WHEN the patient attempts viewing their patient data
        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', 'api/v1/patients/' . $patient->id, $parameters);
    
        // THEN it is successful
        $response->assertStatus(200);
    
        // AND they see their birthdate
        $response->assertJsonFragment(['birthdate' => '1999-01-01']);
    }
    
    public function test_a_patient_cannot_view_another_patients_data()
    {
        // GIVEN patients A and B
        $patientA = factory(Patient::class)->create();
        $patientB = factory(Patient::class)->create();
    
        // WHEN patient A attempts to view patient B's information
        Passport::actingAs($patientA->user);
        $response = $this->json('GET', 'api/v1/patients/' . $patientB->id);
    
        // THEN it is unsuccessful
        $response->assertStatus(401);
    }
}
