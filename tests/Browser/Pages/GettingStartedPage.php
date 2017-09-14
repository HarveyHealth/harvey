<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class gettingStartedPage extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/getting-started';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    public function clickTerms(Browser $browser)
    {
        $browser->check('terms');
    }


    public function clickSignUp(Browser $browser)
    {
        $browser->click("@signUp");
    }


    public function clickBlog(Browser $browser)
    {
       $browser->click("@blog")
               ->waitForText('VISIT SITE')
               ->assertSee("VISIT SITE");
    }

    public function clickInsta(Browser $browser)
    {
       $browser->click('@insta')
               ->waitForText('goharveyapp')
               ->assertSee('goharveyapp');
    }









    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
            '@signUp' => '#app > div > form > div > div > div > div.text-centered > button',
            '@blog' => '#app > div > div > div > div.social-icon-wrapper > a:nth-child(1) > i',
            '@insta' => '#app > div > div > div > div.social-icon-wrapper > a:nth-child(2) > i'
        ];
    }
}
