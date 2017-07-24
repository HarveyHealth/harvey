<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class SignUpPage extends BasePage
{
    public $errorMessages = [
      'first_name' => 'First name is required',
      'last_name' => 'Last name is required',
      'email' => 'Email is required' ,
      'emailnotvalid' => 'Not a valid email address',
      'zipcode' => 'Zipcode is required',
      'nopass' => 'Password is required' ,
      'passshort' => 'Password needs minimum of 6 characters'

    ];



    public function url()
    {
        return '/get-started';
    }


    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }


    public function wrongZip(Browser $browser)
    {
        $browser->type('first_name', 'Alex')
                ->type('last_name', "vaz")
                ->type('email', 'Alex')
                ->type('zip', '99999')
                ->type('password', 'secret')
                ->check('terms')
                ->press('Sign Up');

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
      $browser->waitFor('@first_name')
              ->type('@first_name', $user->first_name)
              ->type('last_name' , $user->last_name)
              ->type('email' , $user->email)
              ->type('zip', '91202')
              ->type('password', bcrypt('secret'))
              ->checkTerms()
              ->clickSignUp();


    }

    public function mandatoryFieldCheck(Browser $browser)
    {
      $browser->click('@first_name')
              ->click('@last_name')
              ->assert($this->errorMessages['first_name'])
              ->click('@email')
              ->assertSee($this->errorMessages['last_name'])
              ->click('@zipcode')
              ->assertSee($this->errorMessages['email'])
              ->click('@password')
              ->assertSee($this->errorMessages['zipcode'])
              ->click('@terms&conditions')
              ->assertSee($this->errorMessages['nopass']);


    }

    public function emailNotValid(Browser $browser)
    {
      $browser->type('email', 'qwe')
              ->click('@password')
              ->assertSee($this->errorMessages['emailnotvalid']);
    }

    public function shortPassword(Browser $browser)
    {
      $browser->type('password', 'asdf')
              ->click('@last_name')
              ->assertSee($this->errorMessages['passshort']);
    }

    public function elements()
    {
        return [
            '@element' => '#selector',
            '@signUp' => '#app > div > form > div > div > div > div.text-centered > button > span',
            '@continue' => '#app > div > div > div > button',
            '@practitioner' => '#app > div > div > div.signup-container.signup-stage-container > div.signup-practitioner-wrapper.cf > div:nth-child(1)',
            '@continuePract' => '#app > div > div > div.signup-container.signup-stage-container > div.text-centered > button',
            '@phone_number' => '#app > div > div > div.signup-container.signup-phone-container.text-centered > div:nth-child(2) > div.input-wrap > input',
            '@sendText' => '#app > div > div > div.signup-container.signup-phone-container.text-centered > div:nth-child(2) > button',
            '@first_name' => '#app > div > form > div > div > div > div:nth-child(2) > input',
            '@last_name' => '#app > div > form > div > div > div > div:nth-child(3) > input',
            '@email' => '#app > div > form > div > div > div > div:nth-child(4) > input',
            '@zipcode' => '#app > div > form > div > div > div > div:nth-child(5) > input',
            '@password' => '#app > div > form > div > div > div > div:nth-child(6) > input',
            '@terms&conditions' => '#app > div > form > div > div > div > div.input-wrap.last'

        ];
    }


}
