<?php

namespace Tests\Browser;

use App\Models\{User, PractitionerSchedule, Practitioner, PractitionerType};
use Tests\Browser\Pages\SignUpPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
                      ->waitForText('First, choose your practitioner...')
                      ->assertSee('First, choose your practitioner...')
                      ->click('@practitioner')
                      ->pause(1000)
                      ->press('@continuePract')
                      ->pause(3000)
                      ->click('@continueDeta')
                      ->pause(1000)
                      ->click('@weekday')
                      ->click('@time')
                      ->click('@confirmTime')
                      ->waitForText('Your appointment is confirmed!')
                      ->assertSee('Your appointment is confirmed!')
                      ->click('@dashboard')
                      ->waitForText('Your Dashboard')
                      ->assertSee('Your Dashboard');

                    });
              $this->assertDatabaseHas('users', ['email' => $user->email]);
              $this->assertDatabaseHas('users', ['first_name' => $user->first_name]);
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
