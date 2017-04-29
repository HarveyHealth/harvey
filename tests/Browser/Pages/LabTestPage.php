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
                ->assertSee('$299')
                ->click('@appointmentbutton')
                ->assertSee('Your journey starts here');
    }

    public function testHormones(Browser $browser)
    {
        $browser->click('@hormones')
                ->waitForText('Hormone Test')
                ->assertSee('Hormone Test')
                ->assertSee('$99')
                ->click('@appointmentbuttontwo')
                ->assertSee('Your journey starts here');
    }

    public function testAdrenals(Browser $browser)
    {
        $browser->click('@adrenals')
                ->waitForText('Sample: Saliva')
                ->assertSee('Sample: Saliva')
                ->assertSee('$125')
                ->click('@appointmentbuttonthree')
                ->assertSee('Your journey starts here');

    }

    public function testThyroid(Browser $browser)
    {
        $browser->click('@thyroid')
                ->waitForText('Thyroid/Cortisol Test')
                ->assertSee('Thyroid/Cortisol Test')
                ->assertSee('$99')
                ->click('@appointmentbuttonfour')
                ->assertSee('Your journey starts here');
    }

    public function testCardio(Browser $browser)
    {
        $browser->click('@cardio')
                ->waitForText('CardioMetabolic Test')
                ->assertSee('CardioMetabolic Test')
                ->assertSee('$99')
                ->click('@appointmentbuttonfive')
                ->assertSee('Your journey starts here');
    }

    public function testCBC(Browser $browser)
    {
        $browser->click('@cbc')
                ->waitForText('CBC/CMP Test')
                ->assertSee('CBC/CMP Test')
                ->assertSee('$29')
                ->click('@appointmentbuttonsix')
                ->assertSee('Your journey starts here');
    }

    public function testToxicMetals(Browser $browser)
    {
        $browser->click('@toxicMetal')
                ->waitForText('Toxic Metals Test')
                ->assertSee('Toxic Metals Test')
                ->assertSee('$199')
                ->click('@appointmentbuttonseven')
                ->assertSee('Your journey starts here');
    }

    public function testToxicChemicals(Browser $browser)
    {
        $browser->click('@toxicChemical')
                ->waitForText('Toxic Chemicals Test')
                ->assertSee('Toxic Chemicals Test')
                ->assertSee('$239')
                ->click('@appointmentbuttoneight')
                ->assertSee('Your journey starts here');
    }

    public function testFoodAllergies(Browser $browser)
    {
        $browser->click('@foodAllergies')
                ->waitForText('Food Allergy Test')
                ->assertSee('Food Allergy Test')
                ->assertSee('$239')
                ->click('@appointmentbuttonnine')
                ->assertSee('Your journey starts here');
    }

    public function testMicrobiome(Browser $browser)
    {
        $browser->click('@microbiome')
                ->waitForText('Microbiome (Gut) Test')
                ->assertSee('Microbiome (Gut) Test')
                ->assertSee('$199')
                ->click('@appointmentbuttonten')
                ->assertSee('Your journey starts here');
    }

    public function testOrganic(Browser $browser)
    {
        $browser->click('@organic')
                ->waitForText('Organic Acids Test')
                ->assertSee('Organic Acids Test')
                ->assertSee('$299')
                ->click('@appointmentbuttoneleven')
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
            '@organic' => '#app > div > section.section.check-load.is-loaded > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(12) > a',
            '@appointmentbutton' => '#tab-1 > div > div > a',
            '@appointmentbuttontwo' => '#tab-2 > div > div > a',
            '@appointmentbuttonthree' => '#tab-3 > div > div > a',
            '@appointmentbuttonfour' => '#tab-4 > div > div > a',
            '@appointmentbuttonfive' => '#tab-5 > div > div > a',
            '@appointmentbuttonsix' => '#tab-6 > div > div > a',
            '@appointmentbuttonseven' => '#tab-7 > div > div > a',
            '@appointmentbuttoneight' => '#tab-8 > div > div > a',
            '@appointmentbuttonnine' => '#tab-9 > div > div > a',
            '@appointmentbuttonten' => '#tab-10 > div > div > a',
            '@appointmentbuttoneleven' => '#tab-11 > div > div > a'
        ];
    }
}
