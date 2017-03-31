<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class LabsPage extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/lab-tests';
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
    public function elements()
    {
        return [
            '@micronutrients' => '#app > div > section.section.check-load > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(1) > a',
            '@hormones' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(3) > a',
            '@thyroid' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(4) > a',
            '@cardiometa' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(5) > a',
            '@CBC' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(6) > a',
            '@toxicmetals' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(7) > a',
            '@toxicchemicals' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(8) > a',
            '@foodallergies' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(9) > a',
            '@microbiome' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(10) > a',
            '@organicacids' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(11) > a'
        ];
    }
}
