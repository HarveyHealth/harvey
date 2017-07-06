<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class LoginPage extends BasePage
{

  public $signupPage = "Your journey starts here";
  public $forgotPasswordPage = "Reset your password";
  public $homepage = "Hi. We're Harvey. We specialize in complex health conditions.";

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
               ->assertSee($this->signupPage);
    }

    public function logoHeader(Browser $browser)
    {
       $browser->click('@logoHeader')
               ->assertSee($this->homepage);
    }







    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@login' => '#login > p.control.is-clearfix > button',
            '@bookNowHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(3)',
            '@forgotPassword' => '#login > div > div > p.control.is-clearfix > a',
            '@signUpButton' => '#app > div > section > div > div > footer > div > a',
            '@logoHeader' => '#app > div.header.nav.is-inverted > div > div.nav-left > a > div > svg',
            '@logout' => '#app > div.nav-bar > nav > a.admin-nav-link.logout'
        ];
    }
}
