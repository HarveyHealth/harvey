<?php

namespace Tests\Feature\Api\Alpha;

use App\Models\User;
use App\Models\Appointment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AppointmentTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;
    
    public function test_it_shows_the_patient_their_appointments_only()
    {
        // GIVEN our user has 5 appointments
        $appointment_count = 5;
        $user = factory(User::class)->states('patient')->create();
        factory(Appointment::class, $appointment_count)->create([
            'patient_user_id' => $user->id
        ]);
        
        // AND there are other appointments in the database
        factory(Appointment::class, 10)->create();
        
        // WHEN the user requests to list out the appointments
        $results = $this->call(
                'GET',
                'api/alpha/appointments',
                ['api_token' => $user->api_token]
        );

        $json = json_decode($results->content());
        
        // THEN we should see 5 of them
        $this->assertEquals(count($json), $appointment_count);
    }
    
    public function test_it_shows_an_admin_all_appointments()
    {
        // GIVEN there are 5 appointments for random patients
        $appointment_count = 5;
        factory(Appointment::class, $appointment_count)->create();
        
        // AND an admin calls the appointments route
        $user = factory(User::class)->states('admin')->create();
    
        // WHEN the admin requests to list out the appointments
        $results = $this->call(
            'GET',
            'api/alpha/appointments',
            ['api_token' => $user->api_token]
        );

        $json = json_decode($results->content());
    
        // THEN we should see 5 of them
        $this->assertEquals(count($json), $appointment_count);
    }
}
