<?php

 namespace Tests\Browser\Pages;

 use Laravel\Dusk\Browser;
 use Laravel\Dusk\Page as BasePage;
 use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomePage extends Page
{


    public $signupText = "Personalized for better health.";
    public $coverTitle = "Choose better health.";
    public $labsPage = 'Micronutrients Test';
    public $helpPage =  'Advice and answers from the Harvey Team';
    public $aboutPage = 'Harvey empowers people to find natural and holistic remedies to chronic health conditions.';


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

    public function financingHeader(Browser $browser)
    {
        $browser->click('@financingHeader')
                ->waitForText('0% Loan Financing')
                ->assertSee('0% Loan Financing');
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
            $browser->waitFor('@labsTestButton')
                    ->click('@labsTestButton')
                    ->assertSee($this->labsPage);

    }

    //Test footer
    public function homeFooter(Browser $browser)
    {
          $browser->pause(1000)
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

    public function financingFooter(Browser $browser)
    {
          $browser->mouseover('@footerBottom')
                  ->pause(1000)
                  ->click('@footerFinancing')
                  ->assertSee('0% Loan Financing');
    }






    public function elements()
    {
        return [
            '@element' => '#selector',
            '@getStartedHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > div > a:nth-child(2)',
            '@loginHeader' => '#app > div.header.nav > div > div.nav-left > div > a:nth-child(5)',
            '@harveyLogoHeader' => '#app > div.header.nav.is-inverted > div > div.nav-left > a > div > svg',
            '@labTesting' => '#tests > div > h2 > span',
            '@aboutHeader' => '#app > div.header.nav.is-inverted > div > div.nav-left > div > a:nth-child(1)',
            '@labsTestHeader' => '#app > div.header.nav.is-inverted > div > div.nav-left > div > a:nth-child(2)',
            '@pricingHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(3)',
            '@financingHeader' => '#app > div.header.nav > div > div.nav-left > div > a:nth-child(4)',
            '@bookCover' => '#app > div.page-content > div > section.hero.hero-background > div.hero-body > div > div > div > div > a',
            '@bookAppTwo' => '#get-started > div > div > div > a',
            '@labMouseOver' => '#feature > div',
            '@labsTestButton' => '#feature > div > div > div > div > div > a',
            '@footer' => '#app > footer > div > div > a > img',
            '@footerBottom' => '#app > footer > div > div > p.has-small-lineheight > small',
            '@homeFooter' => '#app > footer > div > div > p.nav-center > a:nth-child(1)',
            '@footerLabs' => '#app > footer > div > div > p.nav-center > a:nth-child(3)',
            '@footerBlog' => '#app > footer > div > div > p.nav-center > a:nth-child(4)',
            '@footerhelp' => '#app > footer > div > div > p:nth-child(3) > a:nth-child(6)',
            '@footerFinancing' => '#app > footer > div > div > p:nth-child(3) > a:nth-child(4)'

          ];
    }
}
