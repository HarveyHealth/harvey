<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\PractitionerSchedule;
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
        $schedule = factory(PractitionerSchedule::class)->create(['day_of_week' => 'Tuesday', 'start_time' => '08:00:00', 'stop_time' => '17:00:00']);
          $this->browse(function ($browser) use ($user) {
              $browser->visit(new SignUpPage)
                      ->pause(3000)
                      ->addUser($user)
                      ->pause(3000)
                      ->click('@letsgo')
                      ->waitForText('Choose your physician')
                      ->assertSee('Choose your physician')
                      ->click('@practitioner')
                      ->pause(1000)
                      ->press('@continuePract')
                      ->pause(3000)
                      ->type('first_name', $user->first_name)
                      ->type('last_name', $user->last_name)
                      ->type('phone_number', $user->phone)
                      ->click('@continueDeta')
                      ->pause(1000)
                      ->click('@weekday')
                      ->click('@time')
                      ->click('@confirmTime')
                      ->waitForText('Your appointment is confirmed!')
                      ->assertSee('Your appointment is confirmed!');

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
