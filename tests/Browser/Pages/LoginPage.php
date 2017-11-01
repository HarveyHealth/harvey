<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class LoginPage extends BasePage
{

  public $signupPage = "I agree to Harvey's terms and policies.";
  public $forgotPasswordPage = "Enter your email address below and we will send you a link to reset your password.";
  public $homepage = "Choose better health.";
  public $discovery = 'Personalized for better health.';

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }


    public function bookNowHeader(Browser $browser)
    {
        $browser->click('@bookNowHeader')
                ->assertSee($this->signupPage);
    }

    public function forgotPassword(Browser $browser)
    {

        $browser->click('@forgotPassword')
                ->assertSee($this->forgotPasswordPage);
    }



    public function signUpButton(Browser $browser)
    {
       $browser->click('@signUpButton')
               ->assertSee($this->discovery);
    }

    public function logoHeader(Browser $browser)
    {
       $browser->click('@logoHeader')
               ->assertSee($this->homepage);
    }

    public function errorMessages(Browser $browser)
    {
      $browser->click('@first_name')
              ->click('@last_name');

    }

    public function wrongEmail(Browser $browser)
    {
      $browser->type('email','asdfsa@asdfsd.com')
              ->type('password','asdfasd')
              ->press('@login')
              ->pause(2000)
              ->waitForText('These credentials do not match our records.')
              ->assertSee('These credentials do not match our records.');

    }








    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@login' => '#login > footer > div > button',
            '@bookNowHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(3)',
            '@forgotPassword' => '#login > div > div > div:nth-child(4) > p.control.forgot-password.is-clearfix > a',
            '@signUpButton' => '#login > footer > div > a',
            '@logoHeader' => '#app > div.page-content > section > div > div > a > svg',
            '@logout' => '#app > div.nav-bar > nav > a.admin-nav-link.logout',
            '@login' => '#login > footer > div > button'

        ];
    }
}
