<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\Browser\Pages\SignUpPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class signUpTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_page_load()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new SignUpPage)
                    ->waitForText('Your journey starts here')
                    ->assertSee('Your journey starts here');
        });
    }

    public function test_if_user_is_show_message_for_wrong_zipcode_format()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new SignUpPage)
                    ->type('zipcode', '4')
                    ->type('email', 'a')
                    ->assertSee('The zipcode field must be numeric and exactly contain 5 digits.');
        });
    }

    public function test_no_input_return_messages()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new SignUpPage)
                    ->clickSignUp()
                    ->assertSee('The zipcode field is required.')
                    ->assertSee('The email field is required.')
                    ->assertSee('The password field is required.')
                    ->assertSee('The terms field is required.');
        });
    }

    public function test_if_invalid_email_recieve_message()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new SignUpPage)
                ->type('email','asd')
                ->type('password','asdf')
                ->assertSee('The email field must be a valid email.');
        });
    }

    public function test_if_terms_and_privacy_buttons_redirect()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new SignUpPage)
                    ->clickTerms()
                    ->visit(new SignUpPage)
                    ->clickPrivacy();
        });
    }


    public function test_user_is_created_and_sent_to_next_page()
    {
        $user = factory(User::class)->make();


          $this->browse(function ($browser) use ($user) {
              $browser->visit(new SignUpPage)
                      ->addUser($user);

                    });

              $this->assertDatabaseHas('users', ['email' => $user->email]);
    }
}
