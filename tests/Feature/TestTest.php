<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Practitioner;
use App\Models\Test;
use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TestTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_an_admin_can_view_all_results()
    {
        // Given an admin and a test
        $admin = factory(Admin::class)->create();
        $test = factory(Test::class)->create();
        
        // When the admin tries to access the test information
        Passport::actingAs($admin->user);
        $response = $this->json('GET', "api/v1/tests/{$test->id}");
        
        // It is successful
        $response->assertStatus(200);
        
        // And the test data is displayed
        $this->assertEquals($response->original['data']['attributes']['sku_id'], $test->sku_id);
    }
    
    public function test_a_practitioner_can_view_tests_from_their_patients()
    {
        // Given a practitioner and a test that they are associated with
        $practitioner = factory(Practitioner::class)->create();
        $test = factory(Test::class)->create([
            'practitioner_id' => $practitioner->id
        ]);
    
        // When the admin tries to access the test information
        Passport::actingAs($practitioner->user);
        $response = $this->json('GET', "api/v1/tests/{$test->id}");
    
        // It is successful
        $response->assertStatus(200);
    
        // And the test data is displayed
        $this->assertEquals($response->original['data']['attributes']['sku_id'], $test->sku_id);
    }
    
    public function test_a_practitioner_can_not_view_tests_that_are_not_associated_with_them()
    {
        // Given a practitioner and a test that they are associated with
        $practitioner = factory(Practitioner::class)->create();
        $test = factory(Test::class)->create();
        
        // When the practitioner tries to access the test information
        Passport::actingAs($practitioner->user);
        $response = $this->json('GET', "api/v1/tests/{$test->id}");
        
        // It is not successful
        $response->assertStatus(401);
        
        // And an appropriate error message is shown
        $response->assertSee('Unauthorized Access');
    }
    
    public function test_a_patient_can_view_their_own_tests()
    {
        // Given a practitioner and a test that they are associated with
        $patient = factory(Patient::class)->create();
        $test = factory(Test::class)->create([
            'patient_id' => $patient->id
        ]);
    
        // When the admin tries to access the test information
        Passport::actingAs($patient->user);
        $response = $this->json('GET', "api/v1/tests/{$test->id}");
    
        // It is successful
        $response->assertStatus(200);
    
        // And the test data is displayed
        $this->assertEquals($response->original['data']['attributes']['sku_id'], $test->sku_id);
    }
    
    public function test_a_patient_can__not_view_other_patient_tests()
    {
        // Given a practitioner and a test that they are associated with
        $patient = factory(Patient::class)->create();
        $test = factory(Test::class)->create();
        
        // When the admin tries to access the test information
        Passport::actingAs($patient->user);
        $response = $this->json('GET', "api/v1/tests/{$test->id}");
    
        // It is not successful
        $response->assertStatus(401);
    
        // And an appropriate error message is shown
        $response->assertSee('Unauthorized Access');
    }
}
