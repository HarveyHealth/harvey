<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class AboutPage extends BasePage
{

    public $foodSens = 'Food Sensitivity Test';
    public $labTests = 'Lab Tests';
    public $supplements = 'Supplements';
    public $consultations = 'Book a consultation with';
    public $microNutrient = 'Micronutrient Test';
    public $microbiome = 'Microbiome Test';
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/about';
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

    public function clickAssert(Browser $browser, $selector, $assertion)
    {
        $browser->click($selector)
                ->waitForText($this->$assertion)
                ->assertSee($this->$assertion);
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
            '@foodSensitivities' => '#products > div > div > div:nth-child(1) > div.pa2 > p.f5-l.f6.pa3-l.pa2 > a:nth-child(1)',
            '@micronutrient' => '#products > div > div > div:nth-child(1) > div.pa2 > p.f5-l.f6.pa3-l.pa2 > a:nth-child(2)',
            '@microbiome' => '#products > div > div > div:nth-child(1) > div.pa2 > p.f5-l.f6.pa3-l.pa2 > a:nth-child(3)',
            '@labTestShop' => '#products > div > div > div:nth-child(1) > div.pa2 > a',
            '@supplementsShop' => '#products > div > div > div:nth-child(2) > div.pa2 > a',
            '@meetDoctors' => '#products > div > div > div:nth-child(3) > div.pa2 > a',
            '@footer' => '#footer > div:nth-child(1)'
        ];
    }
}
