<?php

namespace Tests\Browser;

use App\Models\{User, Patient, Admin, Practitioner};
use App\Models\PractitionerSchedule;
use Tests\Browser\Pages\dashboardAppointment;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class AppointmentTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
     public function test_if_user_can_create_an_appointment()
     {
         $schedule = factory(PractitionerSchedule::class)->create([
           'day_of_week' => 'Tuesday',
           'start_time' => '08:00:00',
           'stop_time' => '17:00:00']);

         //creates the admin user modelfactory
         factory(Admin::class)->create();

         //creates user and Practitioner
         $user = factory(Patient::class)->create();
         $practitioner = factory(Practitioner::class)->create();

         //grabs data needed from Database

        $client = DB::table('users')->where('id', 3)->value('last_name');
        $client .= ', ';
        $client .= DB::table('users')->where('id', 3)->value('first_name');

        $doctor = 'Dr. ';
        $doctor .= DB::table('users')->where('id', 4)->value('first_name');
        $doctor .= ' ';
        $doctor .= DB::table('users')->where('id', 4)->value('last_name');

         $this->browse(function ($browser) use ($client, $doctor) {
             $browser->loginAs(Admin::find(1))
                     ->visit(new Dashboardappointment)
                     ->click("@appointmentTab")
                     ->waitFor('@newAppointment')
                     ->click('@newAppointment')
                     ->waitFor('@selectPatient')

                     ->select('@selectPatient', $client);
                    //  ->click('@selectDoctor', $doctor);


         });


     }




   }
