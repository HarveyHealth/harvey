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

    public function getStartedHeader(Browser $browser){
        $browser->click('@getStardedHeader')
                ->assertSee('Your journey starts here');
    }

    public function getStartedCover(Browser $browser){
        $browser->click('@getStartedCover')
                ->assertSee('Your journey starts here');
    }

    public function getStartedOne(Browser $browser){
        $browser->click('@getStartedOne')
                ->assertSee('Your journey starts here');
    }

    public function getStartedTwo(Browser $browser){
        $browser->mouseover('@footer')
                ->click('@getStaredTwo')
                ->assertSee('Your journey starts here');
    }


    public function elements()
    {
        return [
            '@element' => '#selector',
            '@getStardedHeader' => '#app > nav > div > div.nav-right > span > a:nth-child(2)',
            '@getStartedCover' => '#app > div > div > section.hero.is-fullheight.is-primary > div.hero-body > div > div > div > div > a',
            '@getStartedOne' => '#how-it-works > div > div.button-wrapper.has-text-centered > a',
            '@getStaredTwo' => '#get-started > div:nth-child(2) > div > div > button > span',
            '@footer' => '#app > footer > div > div > a > img'

          ];
    }
}
