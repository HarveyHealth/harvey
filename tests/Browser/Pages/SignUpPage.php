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

    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }
}
