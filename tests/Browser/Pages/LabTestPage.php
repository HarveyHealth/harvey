<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class LabTestPage extends BasePage
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
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    public function testMicronutrients(Browser $browser)
    {
        $browser->assertSee('Micronutrient Test')
                ->assertSee('$299');
    }

    public function testHormones(Browser $browser)
    {
        $browser->click('@hormones')
                ->assertSee('Hormone Test')
                ->assertSee('$99');
    }

    public function testAdrenals(Browser $browser)
    {
        $browser->click('@adrenals')
                ->assertSee('Sample: Saliva')
                ->assertSee('$125');
    }

    public function testThyroid(Browser $browser)
    {
        $browser->click('@thyroid')
                ->assertSee('Thyroid/Cortisol Test')
                ->assertSee('$99');
    }

    public function testCardio(Browser $browser)
    {
        $browser->click('@cardio')
                ->assertSee('CardioMetabolic Test')
                ->assertSee('$99');
    }

    public function testCBC(Browser $browser)
    {
        $browser->click('@cbc')
                ->assertSee('CBC/CMP Test')
                ->assertSee('$29');
    }

    public function testToxicMetals(Browser $browser)
    {
        $browser->click('toxicMetal')
                ->assertSee('Toxic Metals Test')
                ->assertSee('$199')
                ->click('Book Appointment')
                ->assertSee('Your journey starts here');
    }

    public function testToxicChemicals(Browser $browser)
    {
        $browser->click('@toxicChemical')
                ->assertSee('Toxic Chemicals Test')
                ->assertSee('$239')
                ->click('Book Appointment')
                ->assertSee('Your journey starts here');
    }

    public function testFoodAllergies(Browser $browser)
    {
        $browser->click('@foodAllergies')
                ->assertSee('Food Allergy Test')
                ->assertSee('$239')
                ->click('Book Appointment')
                ->assertSee('Your journey starts here');
    }

    public function testMicrobiome(Browser $browser)
    {
        $browser->click('@microbiome')
                ->assertSee('Microbiome (Gut) Test')
                ->assertSee('$199')
                ->click('Book Appointment')
                ->assertSee('Your journey starts here');
    }

    public function testOrganic(Browser $browser)
    {
        $browser->click('@organic')
                ->assertSee('Organic Acids Test')
                ->assertSee('$299')
                ->click('Book Appointment')
                ->assertSee('Your journey starts here');
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
            '@hormones' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(3) > a',
            '@adrenals' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(4) > a',
            "@thyroid" => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(5) > a',
            '@cardio' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(6) > a',
            '@cbc' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(7) > a',
            '@toxicMetal' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(8) > a',
            '@toxicChemical' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(9) > a',
            '@foodAllergies' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(10) > a',
            '@microbiome' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(11) > a',
            '@organic' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(12) > a'
        ];
    }
}
