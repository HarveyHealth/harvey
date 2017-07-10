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
    public function test_if_appointments_links_to_appointment_on_appointment_page()
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
                    ->waitFor('@appointment')
                    ->click('@appointment')
                    ->waitForText('Your Appointments')
                    ->assertSee('Your Appointments')
                    ->assertSee('Update Appointment')
                    ->assertSee('Wednesday, July 12th');

        });
    }
}
