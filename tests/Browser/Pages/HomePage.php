<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class HomePage extends Page
{
    public $signupText = 'Create an account';

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



    public function getStartedCover(Browser $browser){
        $browser->click('@getStartedCover')
                ->assertSee($this->signupText);
    }

    public function getStartedOne(Browser $browser){
        $browser->click('@getStartedOne')
                ->assertSee($this->signupText);
    }

    public function getStartedTwo(Browser $browser){
        $browser->mouseover('@footer')
                ->pause(2000)
                ->click('@getStaredTwo')
                ->assertSee($this->signupText);
    }


    public function elements()
    {
        return [
            '@element' => '#selector',
            '@getStartedCover' => '#app > div > div > section.hero.is-fullheight.is-primary > div.hero-body > div > div > div > div > a',
            '@getStartedOne' => '#how-it-works > div > div.button-wrapper.has-text-centered > a',
            '@getStaredTwo' => '#get-started > div:nth-child(2) > div > div > button > span',
            '@footer' => '#app > footer > div'

          ];
    }
}
