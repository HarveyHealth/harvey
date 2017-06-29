<?php

namespace Tests\Browser;


use App\Models\User;
use App\Models\PractitionerSchedule;
use Tests\Browser\Pages\gettingStartedPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class zipcodeInterTest extends DuskTestCase
{
    use DatabaseMigrations;
    public $badZipCopy = "Unfortunately, we can not service clients in your state yet, but we’re working on it. We will add you to our newsletter and let you know as soon as we launch there";
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_if_user_is_sent_to_intersteller_page()
    {
        $user = factory(User::class)->make();
        $schedule = factory(PractitionerSchedule::class)->create(['day_of_week' => 'Tuesday', 'start_time' => '08:00:00', 'stop_time' => '12:00:00']);
          $this->browse(function ($browser) use ($user) {
              $browser->visit(new gettingStartedPage)
                      ->type("first_name" , $user->first_name)
                      ->type("last_name" , $user->last_name)
                      ->type("email" , $user->email)
                      ->type("zipcode" , "90000")
                      ->type("password" , bcrypt("secret"))
                      ->clickTerms()
                      ->clickSignUp()
                      ->waitForText($this->badZipCopy)
                      ->assertSee($this->badZipCopy);

                    });

    }


}
