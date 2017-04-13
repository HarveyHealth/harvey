<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class HomePage extends Page
{


    public $signupText = 'I agree to terms and privacy policy.';
    public $coverTitle = 'Personalized and integrative medicine, unique as you are.';
    public $labsPage = 'Micronutrient Test';


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

//********************Header Tests
    public function getStartedHeader(Browser $browser)
    {
        $browser->click('@getStartedHeader')
                ->assertSee($this->signupText);
    }

    public function loginHeader(Browser $browser)
    {
        $browser->click('@loginHeader')
                ->assertSee('Remember me');
    }

    public function logoHeader(Browser $browser)
    {
        $browser->click('@harveyLogoHeader')
                ->assertSee($this->coverTitle);
    }

//***************Get Started buttons on the page

    public function getStartedCover(Browser $browser)
    {
        $browser->click('@getStartedCover')
                ->assertSee($this->signupText);
    }

    public function getStartedOne(Browser $browser)
    {
        $browser->click('@getStartedOne')
                ->assertSee($this->signupText);
    }

    public function getStartedTwo(Browser $browser)
    {
        $browser->mouseover('@footer')
                ->pause(2000)
                ->click('@getStaredTwo')
                ->assertSee($this->signupText);
    }



    //"Labs Test and Pricing Button test

    public function labsButton(Browser $browser)
    {
            $browser->pause(2000)
                    ->mouseover('@labsTestDiv')
                    ->click('@labsTestButton')
                    ->assertSee($this->labsPage);

    }

    //Test footer

    public function labsFooter(Browser $browser)
    {
          $browser->mouseover('@footer')
                  ->pause(1000)
                  ->mouseover('@footer')
                  ->click('@footerLabs')
                  ->assertSee($this->labsPage);
    }

    public function termsFooter(Browser $browser)
    {
          $browser->mouseover('@footer')
                  ->pause(1000)
                  ->mouseover('@footer')
                  ->click('@footerTerms')
                  ->assertSee('Terms and Conditions');
    }

    public function privacyFooter(Browser $browser)
    {
          $browser->mouseover('@footer')
                  ->pause(1000)
                  ->mouseover('@footer')
                  ->click('@footerPrivacy')
                  ->assertSee('Privacy Policy');
    }




    public function elements()
    {
        return [
            '@element' => '#selector',
            '@getStartedHeader' => '#app > nav > div > div.nav-right > span > a:nth-child(2)',
            '@loginHeader' => '#app > nav > div > div.nav-right > span > a.button.is-primary.is-outlined',
            '@harveyLogoHeader' => '#app > nav > div > div.nav-left > a > div > svg',
            '@getStartedCover' => '#app > div > div > section.hero.is-fullheight.is-primary > div.hero-body > div > div > div > div > a',
            '@getStartedOne' => '#how-it-works > div > div.button-wrapper.has-text-centered > a',
            '@getStaredTwo' => '#get-started > div:nth-child(2) > div > div > button > span',
            '@labsTestDiv' => '#pricing > div > div > div',
            '@labsTestButton' => '#pricing > div > div > div > div > div > a',
            '@footer' => '#app > footer > div > div',
            '@footerLabs' => '#app > footer > div > div > p.nav-center > a:nth-child(2)',
            '@footerTerms' => '#app > footer > div > div > p.nav-center > a:nth-child(5)',
            '@footerPrivacy' => '#app > footer > div > div > p.nav-center > a:nth-child(6)'

          ];
    }
}
