<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class Footer extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#selector';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }


    public function clickAssert(Browser $browser, $mouseOver, $selector, $assertion, $url)
    {
         $browser->mouseOver($mouseOver)
                 ->click($selector)
                 ->waitForText($this->$assertion)
                 ->assertSee($this->$assertion)
                 ->visit($url);
    }

    public function footerTest(Browser $browser, $url)
    {
        $browser->clickAssert($mouseOver, $selector, $assertion, $url);
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }
}
