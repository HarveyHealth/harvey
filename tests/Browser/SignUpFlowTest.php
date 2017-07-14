<?php

namespace Tests\Browser;

use App\Models\{User, PractitionerSchedule, Practitioner, PractitionerType, Patient};
use Tests\Browser\Pages\SignUpPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
//Artisan::call('migrate:refresh', ['--seed' => true]);

class signUpFlowTest extends DuskTestCase
{
    use DatabaseMigrations;


    /**
     * A Dusk test example.
     *
     * @return void
     */

    public function test_if_user_can_get_through_the_sign_up_flow()
    {

        $user = factory(User::class)->make();




        // $patient = factory(Patient::class)->create();
        // $user = $patient->user;

        $schedule = factory(PractitionerSchedule::class)
                  ->create(
                    [

                      'day_of_week' => 'Tuesday',
                      'start_time' => '08:00:00',
                      'stop_time' => '17:00:00'

                   ]);

         factory(Practitioner::class)->create();
         factory(PractitionerType::class)->create();








          $this->browse(function ($browser) use ($user) {
              $browser->visit(new SignUpPage)
                      ->addUser($user)
                      ->waitFor('@continue')
                      ->click('@continue')
                      ->waitFor('@practitioner')
                      ->click('@practitioner')
                      ->waitFor('@continuePract')
                      ->click('@continuePract')
                      ->waitFor('@phone_number')
                      ->type('phone_number', $user->phone)
                      ->click('@sendText')
                      ->pause(3000);
                    });

                $userId = 3;
                $code = Redis::get("phone_validation:{$userId}:{$user->phone}");
                dd($code);
                $this->assertDatabaseHas('users', ['email' => $user->email]);
                $this->assertDatabaseHas('users', ['first_name' => $user->first_name]);

                    $this->browse(function ($browser) use ($user) {
                        $browser->waitForText('First, choose your practitioner...');


                              });


    }

    // public function test_if_succesfull_signup_page_loads_and_next_step()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit(new SignUpPage)
    //                 ->assertSee('Your journey starts here')
    //                 ->assertSee('Choose your physician');
    //     });
    // }
}
