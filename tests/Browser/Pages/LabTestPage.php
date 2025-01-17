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
        $browser->waitForText('Micronutrient Test')
                ->assertSee('Micronutrient Test')
                ->assertSee('$299');
    }

    public function testHormones(Browser $browser)
    {
        $browser->click('@hormones')
                ->waitForText('Hormone Test')
                ->assertSee('Hormone Test')
                ->assertSee('$99');
    }

    public function testAdrenals(Browser $browser)
    {
        $browser->click('@adrenals')
                ->waitForText('Sample: Saliva')
                ->assertSee('Sample: Saliva')
                ->assertSee('$125');

    }

    public function testThyroid(Browser $browser)
    {
        $browser->click('@thyroid')
                ->waitForText('Thyroid/Cortisol Test')
                ->assertSee('Thyroid/Cortisol Test')
                ->assertSee('$99');
    }

    public function testCardio(Browser $browser)
    {
        $browser->click('@cardio')
                ->waitForText('CardioMetabolic Test')
                ->assertSee('CardioMetabolic Test')
                ->assertSee('$99');
    }

    public function testCBC(Browser $browser)
    {
        $browser->click('@cbc')
                ->waitForText('CBC/CMP Test')
                ->assertSee('CBC/CMP Test')
                ->assertSee('$29');
    }

    public function testToxicMetals(Browser $browser)
    {
        $browser->click('@toxicMetal')
                ->waitForText('Toxic Metals Test')
                ->assertSee('Toxic Metals Test')
                ->assertSee('$199');
    }

    public function testToxicChemicals(Browser $browser)
    {
        $browser->click('@toxicChemical')
                ->waitForText('Toxic Chemicals Test')
                ->assertSee('Toxic Chemicals Test')
                ->assertSee('$199');
    }

    public function testFoodAllergies(Browser $browser)
    {
        $browser->click('@foodAllergies')
                ->waitForText('Food Allergy Test')
                ->assertSee('Food Allergy Test')
                ->assertSee('$199');
    }

    public function testMicrobiome(Browser $browser)
    {
        $browser->click('@microbiome')
                ->waitForText('Microbiome (Gut) Test')
                ->assertSee('Microbiome (Gut) Test')
                ->assertSee('$199');
    }

    public function testOrganic(Browser $browser)
    {
        $browser->click('@organic')
                ->waitForText('Organic Acids Test')
                ->assertSee('Organic Acids Test')
                ->assertSee('$299');
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
            '@organic' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(12) > a',
        ];
    }
}
