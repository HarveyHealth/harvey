<?php

namespace Tests\Feature\Api\Alpha;

use App\Models\Patient;
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
        
        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create([
            'user_id' => $user->id
        ]);
        
        factory(Appointment::class, $appointment_count)->create([
            'patient_id' => $patient->id
        ]);
        
        // AND there are other appointments in the database
        factory(Appointment::class, 10)->create();
        
        // WHEN the user requests to list out the appointments
        $response = $this->call(
                'GET',
                'api/alpha/appointments',
                ['api_token' => $user->api_token]
        );
        $content = json_decode($response->content());
        
        // THEN we should see 5 of them
        $this->assertEquals(count($content->data), $appointment_count);
    }
    
//    public function test_it_shows_an_admin_all_appointments()
//    {
//        // GIVEN there are 5 appointments for random patients
//        $appointment_count = 5;
//        factory(Appointment::class, $appointment_count)->create();
//
//        // AND an admin calls the appointments route
//        $user = factory(User::class)->states('admin')->create();
//
//        // WHEN the admin requests to list out the appointments
//        $response = $this->call(
//            'GET',
//            'api/alpha/appointments',
//            ['api_token' => $user->api_token]
//        );
//
//        $content = json_decode($response->content());
//
//        // THEN we should see 5 of them
//        $this->assertEquals(count($content->data), $appointment_count);
//    }
//
//    public function test_it_only_displays_past_appointments_when_using_filter_recent()
//    {
//        // GIVEN our user has 5 past appointments
//        $appointment_count = 5;
//        $user = factory(User::class)->states('patient')->create();
//        factory(Appointment::class, $appointment_count)
//            ->states('past')
//            ->create(['patient_user_id' => $user->id]);
//
//        // WHEN the user requests to list out the appointments
//        // AND uses the 'recent' filter
//        $response = $this->call(
//            'GET',
//            'api/alpha/appointments',
//            ['api_token' => $user->api_token, 'filter' => 'recent']
//        );
//        $content = json_decode($response->content());
//
//        // THEN we should see 3 of them since recentScope defaults to 3 results.
//        $this->assertEquals(count($content->data), 3);
//    }
}
