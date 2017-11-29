<?php

 namespace Tests\Browser\Pages;

 use Laravel\Dusk\Browser;
 use Laravel\Dusk\Page as BasePage;
 use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomePage extends Page
{


    public $signupText = "Personalized for better health.";
    public $conditions = "Start your health journey.";
    public $cover = "Choose better health.";
    public $labs = 'Micronutrients Test';
    public $help =  'Advice and answers from the Harvey Team';
    public $about = 'Harvey empowers people to find natural and holistic remedies to chronic health conditions.';
    public $financing = '0% Loan Financing';



    public function url()
    {
        return '/';
    }

    public function assertCoverTitle(Browser $browser)
    {
      $browser->pause(2000)
              ->assertSee($this->cover);
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
                ->waitForText($this->conditions)
                ->assertSee($this->conditions);
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



    public function labsTestHeader(Browser $browser)
    {
        $browser->click('@labsTestHeader')
                ->assertSee($this->labs);
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

//About Harvey on page
    public function clickAbout(Browser $browser, $about)
    {
        $browser->click('@about' . $about)
                ->assertSee($this->about)
                ->visit('/');
    }

//Explore Conditions on page

  public function exploreConditionsCover(Browser $browser)
  {
      $browser->click('@exloreConditionsCover')
              ->waitForText($this->conditions)
              ->assertSee($this->conditions);
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
                    ->assertSee($this->labs);

    }

    //Test footer
    public function footer(Browser $browser, $a)
    {
      $browser->pause(1000)
              ->click('@footer' . $a)
              ->pause(1500)
              ->assertSee($this->$a);
    }







    public function elements()
    {
        return [
            '@element' => '#selector',
            '@getStartedHeader' => '#app > div.nav-wrap.nav-is-sticky > div.nav-container > div > div.nav-right > div.nav-start > a',
            '@loginHeader' => '#app > div.nav-wrap.nav-is-sticky > div.nav-container > div > div.nav-links > a:nth-child(4)',
            '@harveyLogoHeader' => '#app > div.nav-wrap.nav-is-sticky > div.nav-container > div > a',
            '@labTesting' => '#tests > div > h2 > span',
            '@about1' => '#app > div.nav-wrap.nav-is-sticky > div.nav-container > div > div.nav-links > a:nth-child(1)',
            '@about2' => '#app > div.page-content > div > section:nth-child(2) > div > div > a',
            '@labsTestHeader' => '#app > div.nav-wrap.nav-is-sticky > div.nav-container > div > div.nav-links > a:nth-child(2)',
            '@pricingHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(3)',
            '@financingHeader' => '#app > div.header.nav > div > div.nav-left > div > a:nth-child(4)',
            '@exloreConditionsCover' => '#app > div.page-content > div > section.hero.hero-background > div.hero-body > div > div > div > div > a',
            '@bookCover' => '#app > div.page-content > div > section.hero.hero-background > div.hero-body > div > div > div > div > a',
            '@bookAppTwo' => '#get-started > div > div > div > a',
            '@labMouseOver' => '#feature > div',
            '@labsTestButton' => '#feature > div > div > div > div > div > a',
            '@footer' => '#app > footer > div > div > a > img',
            '@footerBottom' => '#app > footer > div > div > p.has-small-lineheight > small',
            '@footercover' => '#app > footer > div > div > p:nth-child(3) > a:nth-child(1)',
            '@footerlabs' => '#app > footer > div > div > p.nav-center > a:nth-child(3)',
            '@footerBlog' => '#app > footer > div > div > p.nav-center > a:nth-child(4)',
            '@footerhelp' => '#app > footer > div > div > p:nth-child(3) > a:nth-child(6)',
            '@footerfinancing' => '#app > footer > div > div > p:nth-child(3) > a:nth-child(4)'

          ];
    }
}
