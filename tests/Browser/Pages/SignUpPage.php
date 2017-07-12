<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class SignUpPage extends BasePage
{
    public function url()
    {
        return '/getting-started';
    }


    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    public function submitForm(Browser $browser, $temp_user)
    {
        $browser->type('first_name', $temp_user->first_name)
              ->type('last_name', $temp_user->last_name)
              ->type('email', $temp_user->email)
              ->type('phone', $temp_user->phone)
              ->type('password', bcrypt('secret'))
              ->check('terms')
              ->press('Sign Up')
              ->pause(2000)
              ->waitForText('Pick a date')
              ->assertSee('Pick a date')
              ->assertSee('Details');
    }

    public function clickSignUp(Browser $browser)
    {
      $browser->click('@signUp');
    }

    public function clickTerms(Browser $browser)
    {
      $browser->click('@terms')
              ->assertSee('Terms and Conditions');
    }

    public function clickPrivacy(Browser $browser)
    {
      $browser->click('@privacy')
              ->assertSee('Please Read Carefully');
    }

    public function checkTerms(Browser $browser)
    {
      $browser->check('terms');
    }

    public function addUser(Browser $browser, $user)
    {
      $browser->type('first_name', $user->first_name)
              ->type('last_name' , $user->last_name)
              ->type('email' , $user->email)
              ->type('zip', '91202')
              ->type('password', bcrypt('secret'))
              ->checkTerms()
              ->clickSignUp()
              ->waitForText("You will need to answer a few questions")
              ->assertSee("You will need to answer a few questions");


    }

    public function elements()
    {
        return [
            '@element' => '#selector',
            '@signUp' => '#app > div > form > div > div > div > div.text-centered > button'

        ];
    }


}
