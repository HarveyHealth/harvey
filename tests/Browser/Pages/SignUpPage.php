<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class SignUpPage extends BasePage
{
    public function url()
    {
        return '/signup';
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
      $browser->type('zipcode', '91202')
              ->type('email', $user->email)
              ->type('password', bcrypt('secret'))
              ->checkTerms()
              ->clickSignUp()
              ->waitForText("Success! Let's get started.")
              ->assertSee("Success! Let's get started.");


    }

    public function elements()
    {
        return [
            '@element' => '#selector',
            '@signUp' => '#app > div > form > div.text-centered > input',
            '@page' => '#app > div:nth-child(2) > form > div > div > div.guide-block',
            '@terms' => '#app > div > form > div.container.small > div.signup-form-container > div.input-wrap.text-centered > label > a:nth-child(1)',
            '@privacy' => '#app > div > form > div.container.small > div.signup-form-container > div.input-wrap.text-centered > label > a:nth-child(2)',
            '@checkbox' => '#checkbox',
            '@letsgo' => '#app > div > div > div.text-centered > a',
            '@clickpage' => '#app > div > div > div.container.small.message-container > div > h1',
            '@practitioner' => '#app > div:nth-child(3) > form > div > div.container.large > div > div.flex-wrapper > div:nth-child(2) > label > div',
            '@continuePract' => '#app > div:nth-child(3) > form > div > div.container.large > div > div:nth-child(4) > button',
            '@continueDeta' => '#app > div:nth-child(3) > form > div > div > div.signup-form-container > div.text-centered > a',
            '@weekday' => '#app > div:nth-child(2) > form > div > div.container.large > div > div.flex-wrapper > div:nth-child(1) > div > div:nth-child(3) > ul > li:nth-child(2) > button',
            '@time' => '#app > div:nth-child(2) > form > div > div.container.large > div > div.flex-wrapper > div:nth-child(2) > div > div > ul > li:nth-child(5) > button',
            '@confirmTime' => '#app > div:nth-child(2) > form > div > div.container.large > div > div.text-centered > input'
        ];
    }


}
