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
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @param Browser $browser
     * @return array
     */

     public function getStartedHeader(Browser $browser){
         $browser->click('@getStartedHeader')
                 ->assertSee($this->signupText);
     }



    public function elements()
    {
        return [
            '@element' => '#selector',
            '@getStartedHeader' => '#app > nav > div > div.nav-right > span > a:nth-child(2)'
        ];
    }
}
