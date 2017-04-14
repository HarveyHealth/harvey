<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class LoginPage extends BasePage
{

  public $signupPage = "Your journey starts here";
  public $forgotPasswordPage = "Reset Password";

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


    public function getStartedHeader(Browser $browser)
    {
        $browser->click('@getStartedHeader')
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







    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@login' => '#login > p.control.is-clearfix > button',
            '@getStartedHeader' => '#app > nav > div > div.nav-right > span > a:nth-child(2)',
            '@forgotPassword' => '#login > p.control.is-clearfix > a',
            '@signUpButton' => '#app > div > section > div > div > footer > div > a'
        ];
    }
}
