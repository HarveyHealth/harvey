<?php

namespace Tests\Browser;

use App\Models\{User, Patient, Admin, Practitioner, Appointment};
use App\Models\PractitionerSchedule;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class loginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */


     public function test_if_patient_can_login_and_see_dashboard()
     {
       $user = factory(Appointment::class)->create();

       $patient = DB::table('users')->where('id', $user['id'])->value('email');

       $this->browse(function ($browser) use ($patient){
         $browser->visit(new LoginPage)
                 ->type('email', $patient)
                 ->type('password', 'secret')
                 ->click('@login')
                 ->waitForText('Dashboard')
                 ->assertSee('Dashboard')
                 ->click('@logout');

               });


     }

    public function test_if_a_practitioner_can_login_and_see_dashboard()
    {

        //creates the practitioner user modelfactory
        $user = factory(Practitioner::class)->create();
        //grabs email from the users table
        $practitioner = DB::table('users')->where('id', $user['id'])->value('email');

        $this->browse(function ($browser) use ($practitioner) {
            $browser->visit(new LoginPage)
                    ->type('email', $practitioner)
                    ->type('password', 'secret')
                    ->click('@login')
                    ->waitForText('Dashboard')
                    ->assertSee('Dashboard')
                    ->click('@logout');
        });
    }

    public function test_if_a_admin_can_login_and_see_dashboard()
    {

        //creates the admin user modelfactory
        $user = factory(Admin::class)->create();
        //grabs email from the users table
        $admin = DB::table('users')->where('id', $user['id'])->value('email');

        $this->browse(function ($browser) use ($admin) {
            $browser->visit(new LoginPage)
                    ->type('email', $admin)
                    ->type('password', 'secret')
                    ->click('@login')
                    ->waitForText('APPOINTMENTS')
                    ->assertSee('APPOINTMENTS')
                    ->click('@logout');
        });

    }




}
