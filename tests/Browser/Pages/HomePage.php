<?php

 namespace Tests\Browser\Pages;

 use Laravel\Dusk\Browser;
 use Laravel\Dusk\Page as BasePage;
 use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomePage extends Page
{


    public $signupText = "I agree to Harvey's terms and policies.";
    public $coverTitle = "Choose better health.";
    public $labsPage = 'Micronutrients Test';
    public $helpPage =  'Advice and answers from the Harvey Team';
    public $aboutPage = 'Harvey combines conventional Western therapies';


    public function url()
    {
        return '/';
    }

    public function assertCoverTitle(Browser $browser)
    {
      $browser->pause(2000)
              ->assertSee($this->coverTitle);
    }

    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
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
                ->assertCoverTitle();
    }

    public function aboutHeader(Browser $browser)
    {
        $browser->click('@aboutHeader')
                ->assertSee($this->aboutPage);
    }

    public function labsTestHeader(Browser $browser)
    {
        $browser->click('@labsTestHeader')
                ->assertSee($this->labsPage);
    }

    public function pricingHeader(Browser $browser)
    {
        $browser->click('@pricingHeader')
                ->waitForText('SIMPLE PRICING')
                ->assertSee('SIMPLE PRICING');
    }


//***************Boook Appointment buttons on the page


    public function bookCover(Browser $browser)
    {
        $browser->click('@bookCover')
                ->pause(2000)
                ->assertSee($this->signupText);
    }

    public function bookAppTwo(Browser $browser)
    {
        $browser->mouseover('@footer')
                ->pause(1000)
                ->waitFor('@bookAppTwo')
                ->click('@bookAppTwo')
                ->assertSee($this->signupText);
    }



    //"Labs Test and Pricing Button test

    public function labsButton(Browser $browser)
    {
            $browser->pause(2000)
                    ->mouseover('@joinDiscussion')
                    ->click('@labsTestButton')
                    ->assertSee($this->labsPage);

    }

    //Test footer
    public function homeFooter(Browser $browser)
    {
          $browser->mouseover('@footerBottom')
                  ->pause(1000)
                  ->click('#app > footer > div > div > p.nav-center > a:nth-child(1)')
                  ->assertSee($this->coverTitle);
    }


    public function labsFooter(Browser $browser)
    {
          $browser->mouseover('@footerBottom')
                  ->pause(1000)
                  ->click('@footerLabs')
                  ->assertSee($this->labsPage);
    }

    public function blogFooter(Browser $browser)
    {
          $browser->mouseover('@footerBottom')
                  ->pause(1000)
                  ->click('@footerBlog')
                  ->assertSee('VISIT SITE');
    }

    public function helpFooter(Browser $browser)
    {
          $browser->mouseover('@footerBottom')
                  ->pause(1000)
                  ->click('@footerhelp')
                  ->assertSee($this->helpPage);
    }

    public function termsFooter(Browser $browser)
    {
          $browser->mouseover('@footerBottom')
                  ->pause(1000)
                  ->click('@footerTerms')
                  ->assertSee('Terms and Conditions');
    }

    public function privacyFooter(Browser $browser)
    {
          $browser->mouseover('@footerBottom')
                  ->pause(1000)
                  ->click('@footerPrivacy')
                  ->assertSee('Privacy Policy');
    }




    public function elements()
    {
        return [
            '@element' => '#selector',
            '@getStartedHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(6)',
            '@loginHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(5)',
            '@harveyLogoHeader' => '#app > div.header.nav.is-inverted > div > div.nav-left > a > div > svg',
            '@labTesting' => '#tests > div > h2 > span',
            '@aboutHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(1)',
            '@labsTestHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(2)',
            '@pricingHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(3)',
            '@bookCover' => '#app > div.page-content > div > section.hero.is-primary > div.hero-body > div > div > div > div.button-wrapper > a',
            '@bookAppTwo' => '#get-started > div > div > div > a',
            '@joinDiscussion' => '#email-capture > div > div > h2',
            '@labsTestButton' => '#feature > div > div > div > div > div > a',
            '@footer' => '#app > footer > div > div > a > img',
            '@footerBottom' => '#app > footer > div > div > p.has-small-lineheight > small',
            '@homeFooter' => '#app > footer > div > div > p.nav-center > a:nth-child(1)',
            '@footerLabs' => '#app > footer > div > div > p.nav-center > a:nth-child(3)',
            '@footerBlog' => '#app > footer > div > div > p.nav-center > a:nth-child(4)',
            '@footerhelp' => '#app > footer > div > div > p.nav-center > a:nth-child(5)',
            '@footerTerms' => '#app > footer > div > div > p.nav-center > a:nth-child(6)',
            '@footerPrivacy' => '#app > footer > div > div > p.nav-center > a:nth-child(7)'

          ];
    }
}
