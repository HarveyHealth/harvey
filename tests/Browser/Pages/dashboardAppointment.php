<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class DashboardAppointment extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard';
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

    public function selectPatient(Browser $browser)
    {
        $browser->waitFor('@selectPatient')
                ->select('@selectPatient');
    }

    public function selectDoctor(Browser $browser)
    {
        $browser->waitFor('@selectDoctor')
                ->select('@selectDoctor');
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
            '@appointmentTab' => '#app > div.nav-bar > nav > a:nth-child(3)',
            '@newAppointment' => '#app > div.main-container > div.main-content > div > div > h1 > button',
            '@selectPatient' => '#app > div.main-container > aside > div:nth-child(3) > div > span',
            '@selectDoctor' => '#app > div.main-container > aside > div:nth-child(4) > div > span'
        ];
    }
}
