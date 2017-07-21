<?php

namespace Tests\Browser;

use App\Models\{User, Patient, Admin, Practitioner, Appointment};
use App\Models\PractitionerSchedule;
use Tests\Browser\Pages\DashboardPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

class UserDashboardTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */

     public function test_if_correct_doctor_shows_on_dashboard()
     {


       $appointment = factory(Appointment::class)->create();
       //dd($appointment->practitioner_id);

        $userId = DB::table('practitioners')->where('id', $appointment->practitioner_id)->value('user_id');
       // $this->assertDatabaseHas('users', ['email' => $user->email]);
       $first_name = DB::table('users')->where('id', $userId)->value('first_name');
       $last_name = DB::table('users')->where('id', $userId)->value('last_name');
       $practitioner = "Dr. " . $first_name . ' ' . $last_name . ',';


       $this->browse(function ($browser) use ($practitioner) {
           $browser->loginAs(Patient::find(1))
                   ->visit(new DashboardPage)
                   ->assertSee('Your Dashboard')
                   // ->pause(3000)
                   // ->waitFor('@appointment')
                   // ->click('@appointment')
                   ->waitForText($practitioner)
                   ->assertSee($practitioner);
                   // ->assertSee('Update Appointment')
                   // ->assertSee('Wednesday, July 12th');

       });

     }


    public function test_if_old_appointment_does_not_show_up_in_dashboard()
    {
        //factory(Patient::class)->create();
        $appointment = factory(Appointment::class)->create(
          [

            'appointment_at' => '2017-07-13 06:30:30'

         ]);
        //dd($appointment);
        $this->browse(function (Browser $browser) {
            $browser->loginAs(Patient::find(1))
                    ->visit(new DashboardPage)
                    ->assertSee('Your Dashboard')
                    // ->pause(3000)
                    // ->waitFor('@appointment')
                    // ->click('@appointment')
                    ->waitForText('You have no upcoming appointments.')
                    ->assertSee('You have no upcoming appointments.');
                    // ->assertSee('Update Appointment')
                    // ->assertSee('Wednesday, July 12th');

        });
    }




}
