<?php

 namespace Tests\Browser\Pages;

 use Laravel\Dusk\Browser;
 use Laravel\Dusk\Page as BasePage;
 use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomePage extends Page
{


    public $signupText = "I agree to Harvey's terms and policies.";
    public $coverTitle = "Meet our lead Naturopathic Doctor and learn how a more preventative approach to medicine is changing people's lives for the better.";
    public $labsPage = 'Micronutrients Test';
    public $faqPage =  'Advice and answers from the Harvey Team';


    public function url()
    {
        return '/';
    }

    public function assertCoverTitle(Browser $browser)
    {
      $browser->pause(2000)
              ->waitFor($this->coverTitle)

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
    public function bookNowHeader(Browser $browser)
    {
        $browser->click('@bookNowHeader')
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

//***************Boook Appointment buttons on the page


    public function bookAppOne(Browser $browser)
    {
        $browser->mouseover('@labTesting')
                ->waitFor('@bookAppOne')
                ->click('@bookAppOne')
                ->assertSee($this->signupText);
    }

    public function bookAppTwo(Browser $browser)
    {
        $browser->mouseover('@footer')
                ->pause(2000)
                ->click('@bookAppTwo')
                ->assertSee($this->signupText);
    }



    //"Labs Test and Pricing Button test

    public function labsButton(Browser $browser)
    {
            $browser->pause(2000)
                    ->mouseover('@medicalAdvisors')
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

    public function faqFooter(Browser $browser)
    {
          $browser->mouseover('@footerBottom')
                  ->pause(1000)
                  ->click('@footerFaq')
                  ->assertSee($this->faqPage);
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
            '@bookNowHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(3)',
            '@loginHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a.button.is-primary.is-outlined.is-hidden-mobile',
            '@harveyLogoHeader' => '#app > div.header.nav.is-inverted > div > div.nav-left > a > div > svg',
            '@labTesting' => '#tests > div > h2 > span',
            '@bookAppOne' => '#pricing > div > div.has-text-centered > div > a',
            '@bookAppTwo' => '#get-started > div > div > div > a',
            '@medicalAdvisors' => '#advisors > div > h2 > span',
            '@labsTestButton' => '#labs > div > div > div > div > div > a',
            '@footer' => '#app > footer > div > div > a > img',
            '@footerBottom' => '#app > footer > div > div > p.has-small-lineheight > small',
            '@homeFooter' => '#app > footer > div > div > p.nav-center > a:nth-child(1)',
            '@footerLabs' => '#app > footer > div > div > p.nav-center > a:nth-child(2)',
            '@footerBlog' => '#app > footer > div > div > p.nav-center > a:nth-child(3)',
            '@footerFaq' => '#app > footer > div > div > p.nav-center > a:nth-child(4)',
            '@footerTerms' => '#app > footer > div > div > p.nav-center > a:nth-child(5)',
            '@footerPrivacy' => '#app > footer > div > div > p.nav-center > a:nth-child(6)'

          ];
    }
}
