<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class ConsultationsPage extends BasePage
{
    public $skinIssues = 'Skin Issues';
    public $foodAllergies = 'Food Allergies';
    public $stressAndAnxiety = 'Stress & Anxiety';
    public $digestiveIssues = 'Digestive Issues';
    public $fatigue = 'Fatigue';
    public $weight = 'Weight Loss/Gain';
    public $womansHealth = 'Skin Issues';
    public $generalHealth = 'General Health';

     /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/consultations';
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
            '@mouseOverShop' => '#conditions > div > div:nth-child(4) > div:nth-child(2) > a',
            '@skinIssuesShop' => '#conditions > div > div:nth-child(3) > div:nth-child(1) > a',
            '@foodAllergiesShop' => '#conditions > div > div:nth-child(3) > div:nth-child(2) > a',
            '@stressAndAnxietyShop' => '#conditions > div > div:nth-child(3) > div:nth-child(3) > a',
            '@digestiveIssuesShop' => '#conditions > div > div:nth-child(3) > div:nth-child(4) > a',
            '@fatigueShop' => '#conditions > div > div:nth-child(4) > div:nth-child(1) > a',
            '@weightShop' => '#conditions > div > div:nth-child(4) > div:nth-child(2) > a',
            '@womansHealthShop' => '#conditions > div > div:nth-child(4) > div:nth-child(3) > a',
            '@generalHealthShop' => '#conditions > div > div:nth-child(4) > div:nth-child(4) > a'
        ];
    }
}
