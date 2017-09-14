<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\{User, Patient, Admin, Practitioner, Appointment, LabOrder, Message, License};
use Tests\Browser\Pages\DashboardPage;
use Illuminate\Support\Facades\DB;

class AdminTests extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_if_admin_can_see_dashboard()
    {
        factory(Admin::class)->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs(Admin::find(1))
                    ->visit(new DashboardPage)
                    ->assertSee('Admin Dashboard');
        });
    }

    public function test_if_admin_can_see_appointments_page()
    {
        factory(Admin::class)->create();
        $appointment = factory(Appointment::class)->create();



        $user_id = DB::table('patients')->where('id', $appointment->patient_id)->value('user_id');

        $firstName = DB::table('users')->where('id', $user_id)->value('first_name');



        $this->browse(function ($browser) use ($firstName) {
            $browser->loginAs(Admin::find(1))
                    ->visit(new DashboardPage)
                    ->assertSee('Admin Dashboard')
                    ->waitFor('@adminAppoint')
                    ->click('@adminAppoint')
                    ->waitForText('Your Appointment')
                    ->assertSee('Your Appointment')
                    ->waitForText($firstName)
                    ->assertSee($firstName);
        });
    }

    public function test_if_admin_can_see_laborders_page()
    {
        factory(Admin::class)->create();
        factory(LabOrder::class)->create();
        $labs = factory(LabOrder::class)->create();



        $user_id = DB::table('patients')->where('id', $labs->patient_id)->value('user_id');

        $firstName = DB::table('users')->where('id', $user_id)->value('first_name');



        $this->browse(function ($browser) use ($firstName) {
            $browser->loginAs(Admin::find(1))
                    ->visit(new DashboardPage)
                    ->assertSee('Admin Dashboard')
                    ->waitFor('@adminLabs')
                    ->click('@adminLabs')
                    ->waitForText('Lab Orders')
                    ->assertSee('Lab Orders');
        });
    }


        public function test_if_admin_can_see_messages_page()
        {
            $admin = factory(Admin::class)->create();



            $message = factory(Message::class)->create([
              "recipient_user_id" => $admin->user_id,
              "subject" => "what's up!"
            ]);




            $this->browse(function ($browser) use ($admin) {
                $browser->loginAs(Admin::find($admin->user_id))
                        ->visit(new DashboardPage)
                        ->assertSee('Admin Dashboard')
                        ->waitFor('@adminMessages')
                        ->click('@adminMessages')
                        ->waitForText("what's up!")
                        ->assertSee("what's up!");
            });
        }

        public function test_if_admin_can_see_clients_page()
        {
          factory(Admin::class)->create();
          $patient = factory(Patient::class)->create();

          $first_name = DB::table('users')->where('id', $patient->user_id)->value('first_name');

            $this->browse(function ($browser) use ($first_name) {
                $browser->loginAs(Admin::find(1))
                        ->visit(new DashboardPage)
                        ->assertSee('Admin Dashboard')
                        ->waitFor('@adminClients')
                        ->click('@adminClients')
                        ->waitForText($first_name)
                        ->assertSee($first_name);
            });
        }


        public function test_if_admin_can_see_profile_and_make_changes()
        {
          factory(Admin::class)->create();
          factory(License::class)->create([
            'state' => 'CA'
          ]);


          //DB::table('users')->where('id', $patient->user_id)->value('first_name');

            $this->browse(function (Browser $browser) {
                $browser->loginAs(Admin::find(1))
                        ->visit(new DashboardPage)
                        ->assertSee('Admin Dashboard')
                        ->waitFor('@adminProfile')
                        ->click('@adminProfile')
                        ->waitForText('CONTACT INFO')
                        ->assertSee('CONTACT INFO')
                        ->pause(2000)
                        ->type('first_name', 'Alejandro')
                        ->type('last_name', 'Vazquez')
                        ->type('email', 'alex@vaz.com')
                        ->type('phone', '8138411039')
                        ->type('address_1', '1630 Highland Ave')
                        ->type('city', 'Glendale')
                        ->type('zip', '91202')
                        ->click('@profileSave')
                        ->waitForText('Changes Saved')
                        ->assertSee('Changes Saved');
            });

            $this->assertDatabaseHas('users',[
              'first_name' => 'Alejandro',
              'last_name' => 'Vazquez',
              'email' => 'alex@vaz.com',
              'phone' => '8138411039',
              'city' => 'Glendale',
              'zip' => '91202'
            ]);
        }


        public function test_if_admin_can_see_user_profile_and_make_changes()
        {
          factory(Admin::class)->create();
          $patient = factory(Patient::class)->create();
          factory(License::class)->create([
            'state' => 'CA'
          ]);




          //DB::table('users')->where('id', $patient->user_id)->value('first_name');

            $this->browse(function (Browser $browser) {
                $browser->loginAs(Admin::find(1))
                        ->visit(new DashboardPage)
                        ->assertSee('Admin Dashboard')
                        ->visit('/dashboard#/profile/2')
                        ->waitForText('CONTACT INFO')
                        ->assertSee('CONTACT INFO')
                        ->pause(4000)
                        ->type('first_name', 'Alejandro')
                        ->type('last_name', 'Vazquez')
                        ->type('email', 'alex@vaz.com')
                        ->type('phone', '8138411039')
                        ->type('address_1', '1630 Highland Ave')
                        ->type('city', 'Glendale')
                        ->type('zip', '91202')
                        ->waitFor('@profileSave')
                        ->click('@profileSave')
                        ->waitForText('Changes Saved')
                        ->assertSee('Changes Saved');
            });

            $this->assertDatabaseHas('users',[
                  'first_name' => 'Alejandro',
                  'last_name' => 'Vazquez',
                  'email' => 'alex@vaz.com',
                  'phone' => '8138411039',
                  'city' => 'Glendale',
                  'zip' => '91202'
                ]);
          }

          public function test_if_admin_can_see_practitioner_profile_and_make_changes()
          {
            factory(Admin::class)->create();
            factory(Practitioner::class)->create();
            factory(License::class)->create([
              'state' => 'CA'
            ]);




            //DB::table('users')->where('id', $patient->user_id)->value('first_name');

              $this->browse(function (Browser $browser) {
                  $browser->loginAs(Admin::find(1))
                          ->visit(new DashboardPage)
                          ->assertSee('Admin Dashboard')
                          ->visit('/dashboard#/profile/2')
                          ->waitForText('CONTACT INFO')
                          ->assertSee('CONTACT INFO')
                          ->waitForText('PRACTITIONER PROFILE')
                          ->assertSee('PRACTITIONER PROFILE')
                          ->pause(2000)
                          ->type('first_name', 'Alejandro')
                          ->type('last_name', 'Vazquez')
                          ->type('email', 'alex@vaz.com')
                          ->type('phone', '8138411039')
                          ->type('address_1', '1630 Highland Ave')
                          ->type('city', 'Glendale')
                          ->type('zip', '91202')
                          ->click('@profileSave')
                          ->waitForText('Changes Saved')
                          ->assertSee('Changes Saved');
              });

              $this->assertDatabaseHas('users',[
                    'first_name' => 'Alejandro',
                    'last_name' => 'Vazquez',
                    'email' => 'alex@vaz.com',
                    'phone' => '8138411039',
                    'city' => 'Glendale',
                    'zip' => '91202'
                  ]);
            }



}
