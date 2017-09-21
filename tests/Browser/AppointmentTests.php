<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\{User, Patient, Admin, Practitioner, Appointment, LabOrder, Message, License};
use Tests\Browser\Pages\DashboardAppointment;
use Illuminate\Support\Facades\DB;

class AppointmentTests extends DuskTestCase
{
    public $newAppointmentHeader = 'Book Appointment';


    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
     public function test_admin_tries_to_create_an_appointment_client_no_cc()
     {
         factory(Admin::class)->create();
         factory(Practitioner::class)->create();
         $patient = factory(Patient::class)->create();



         $lastName = DB::table('users')->where('id', $patient->user_id)->value('last_name');
         $firstName = DB::table('users')->where('id', $patient->user_id)->value('first_name');
         $email = DB::table('users')->where('id', $patient->user_id)->value('email');

         //dd($email);
         $fullName = $lastName . ', ' . $firstName;

         $this->browse(function ($browser) use ($fullName) {
             $browser->loginAs(Admin::find(1))
                     ->visit(new DashboardAppointment)
                     ->waitFor('@appointmentTab')
                     ->click('@appointmentTab')
                     ->waitForText('Appointments')
                     ->assertSee('Appointments')
                     ->click('@newAppointment')
                     ->waitForText($this->newAppointmentHeader)
                     ->assertSee($this->newAppointmentHeader)
                     ->pause(10000)
                     ->selectPatient()
                     ->pause(1000)
                     ->selectDoctor()
                     ->pause(5000);


         });
     }
}
