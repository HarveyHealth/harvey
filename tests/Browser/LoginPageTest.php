<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\LoginPage;

class LoginPageTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_if_get_stared_is_linked_in_header()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                    ->getStartedHeader();
        });
    }

    public function test_if_forgot_your_password_routes_to_page()
    {
       $this->browse(function (Browser $browser) {
          $browser->visit(new LoginPage)
                  ->forgotPassword();
          });
    }


    public function test_if_sign_up_button_takes_user_to_register()
    {
      $this->browse(function (Browser $browser) {
        $browser->visit(new LoginPage)
                ->signUpButton();
      });
    }

    public function test_if_logo_redirects_to_homepage()
    {
      $this->browse(function (Browser $browser) {
        $browser->visit(new LoginPage)
                ->logoHeader();
      });
    }


}
