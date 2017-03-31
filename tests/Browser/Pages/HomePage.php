<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class HomePage extends Page
{
    public function url()
    {
        return '/';
    }

    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    public function setFatigue(Browser $browser)
    {
        $browser->drag('@symptom1', '@symptom2');
    }

    public function logout(Browser $browser)
    {
        $browser->press('@accountButton')
            ->clickLink('Logout');
    }

    public function elements()
    {
        return [
            '@element' => '#selector',
            '@symptom1' => '#symptoms > div > div > div.symptoms-container > div > div:nth-child(1) > div.control > div > div > div.vue-slider-always.vue-slider-dot',
            '@symptom2' => '#symptoms > div > div > div.symptoms-container > div > div:nth-child(1) > div.control > div > div > ul > li:nth-child(4)',
            '@footer' => '#app > footer',
            '@getStarted' => '#symptoms > div > div > div.has-text-centered > button > span',
            '@lastNaveBut' => '#app > div > div > nav > ul > li:nth-child(5)',
            '@getStartedCover' => '#app > div > div > section.hero.is-fullheight.is-primary > div.hero-body > div > div > div > div > a',
            '@getStartedOne' => '#app > div > div > section.section.is-paddingless-mobile > div > div > div.column.has-content-vertical-aligned > div > div > a',
            '@getStartedTwo' => '#how-it-works > div > div.button-wrapper.has-text-centered > a',
            '@mouseOverTypeofLabTests' => '#tests > div > h2 > span',
            '@mouseOverLearnTheFacts' => '#app > div > div > section:nth-child(9) > div > h2 > span',
            '@mouseOverHowitWorks' => '#how-it-works > div > h2 > span',
            '@exploreOtherTests' => '#pricing > div > div > div > div > div > a',
            '@mouseOverYourSymptoms' => '#symptoms > div > div > h2 > span',
            '@preventableDeaths' => '#app > div > div > section:nth-child(9) > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(1) > a',
            '@doctorsAndNutrition' => '#app > div > div > section:nth-child(9) > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(2) > a',
            '@specializedlabtests' => '#app > div > div > section:nth-child(9) > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(3) > a',
            '@clinicalEvidence' => '#app > div > div > section:nth-child(9) > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(4) > a',
            '@naturopathicDoctors' => '#app > div > div > section:nth-child(9) > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(5) > a',
            '@dangersOfMultivitamins' => '#app > div > div > section:nth-child(9) > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(6) > a',
            '@taintedVitamins' => '#app > div > div > section:nth-child(9) > div > div > div.column.is-3.tabs-navigation > aside > ul > li:nth-child(7) > a'
        ];
    }
}
