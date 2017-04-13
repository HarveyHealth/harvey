<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class header extends BasePage
{
    public $signupText = 'Create an account';
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
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

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */

     public function getStartedHeader(Browser $browser){
         $browser->click('@getStardedHeader')
                 ->assertSee($this->signupText);
     }



    public function elements()
    {
        return [
            '@element' => '#selector',
            '@getStardedHeader' => '#app > nav > div > div.nav-right > span > a:nth-child(2)'
        ];
    }
}
