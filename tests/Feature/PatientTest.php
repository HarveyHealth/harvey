<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Patient;
use App\Models\Practitioner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_non_valid_patient_id_will_return_404()
    {
        Passport::actingAs(factory(Patient::class)->create()->user);
        $response = $this->json('GET', 'api/v1/patients/1234');

        $response->assertStatus(404);
    }

    public function test_only_admin_or_practitioner_can_view_patients_data()
    {
        $patient = factory(Patient::class, 3)->create();

        foreach ([Admin::class, Practitioner::class] as $userClass) {
            Passport::actingAs(factory($userClass)->create()->user);
            $response = $this->json('GET', 'api/v1/patients/');
            $response->assertStatus(200);
            $this->assertCount(3, $response->original['data']);
        }

        Passport::actingAs(factory(Patient::class)->create()->user);
        $response = $this->json('GET', 'api/v1/patients/');
        $response->assertStatus(401);
    }

    public function test_admin_or_practitioner_can_view_paginated_patients_data()
    {
        $patient = factory(Patient::class, 3)->create();

        foreach ([Admin::class, Practitioner::class] as $userClass) {
            Passport::actingAs(factory($userClass)->create()->user);
            $response = $this->json('GET', 'api/v1/patients/?per_page=2');
            $response->assertStatus(200);
            $this->assertCount(2, $response->original['data']);
            $response->assertJsonFragment([
                'meta' => [
                    'pagination' => [
                        'count' => 2,
                        'current_page' => 1,
                        'per_page' => 2,
                        'total' => 3,
                        'total_pages' => 2,
                    ]
                ]
            ]);
        }
    }

    public function test_a_patient_can_view_their_patient_data()
    {
        // Given a patient
        $patient = factory(Patient::class)->create(['birthdate' => '1950-09-30']);

        // When the patient attempts viewing their patient data
        Passport::actingAs($patient->user);
        $response = $this->json('GET', 'api/v1/patients/' . $patient->id);

        // Then it is successful
        $response->assertStatus(200);

        // And they see their birthdate
        $response->assertJsonFragment(['birthdate' => ["date" => "1950-09-30 00:00:00.000000", "timezone" => "UTC", "timezone_type" => 3]]);
    }

    public function test_a_patient_can_modify_their_patient_data()
    {
        // Given a patient
        $patient = factory(Patient::class)->create(['birthdate' => '1950-09-30']);

        // And a patient's data parameter
        $parameters = ['birthdate' => '1999-01-01'];

        // When the patient attempts viewing their patient data
        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', 'api/v1/patients/' . $patient->id, $parameters);

        // Then it is successful
        $response->assertStatus(200);

        // And they see their birthdate in the response as well as the database
        $response->assertJsonFragment(['birthdate' => ["date" => "1999-01-01 00:00:00.000000", "timezone" => "UTC", "timezone_type" => 3]]);
        $this->assertDatabaseHas('patients', ['birthdate' => '1999-01-01 00:00:00']);
    }

    public function test_birthdate_should_be_a_valid_date_when_updating()
    {
        $patient = factory(Patient::class)->create();
        $parameters = ['birthdate' => 'InvalidDate'];

        Passport::actingAs($patient->user);
        $response = $this->json('PATCH', 'api/v1/patients/' . $patient->id, $parameters);

        $response->assertStatus(400);
    }

    public function test_patient_can_only_edit_his_own_information()
    {
        $patient = factory(Patient::class)->create();
        $patient1 = factory(Patient::class)->create();

        Passport::actingAs($patient1->user);
        $response = $this->json('PATCH', 'api/v1/patients/' . $patient->id);

        $response->assertStatus(401);
    }

    public function test_a_patient_cannot_view_another_patients_data()
    {
        // Given patients A and B
        $patientA = factory(Patient::class)->create();
        $patientB = factory(Patient::class)->create();

        // When patient A attempts to view patient B's information
        Passport::actingAs($patientA->user);
        $response = $this->json('GET', 'api/v1/patients/' . $patientB->id);

        // Then it is unsuccessful
        $response->assertStatus(401);
    }
}
