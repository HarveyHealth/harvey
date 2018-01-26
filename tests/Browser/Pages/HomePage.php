<?php

 namespace Tests\Browser\Pages;

 use Laravel\Dusk\Browser;
 use Laravel\Dusk\Page as BasePage;
 use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomePage extends Page
{
    public $signupText = "Personalized for better health.";
    public $conditions = "Start your health journey.";
    public $cover = "Learn what your body really";
    public $labs = 'Micronutrients Test';
    public $help =  'Advice and answers from the Harvey Team';
    public $about = 'About Harvey';
    public $financing = '0% Loan Financing';
    public $login = 'Remember me';
    public $stories = 'Meet Our Patients';
    public $consultations = 'Book a consultation with';
    public $shopify = 'Learn what your body needs to feel its best.';
    public $shopifyLabs = 'Lab Tests';
    public $shopifySupplements = 'Supplements';
    public $terms = 'Place Holder';
    public $privacy = 'Place Holder';
    public $skinIssues = 'Skin Issues';
    public $foodAllergy = 'Food Allergies';
    public $stressAndAnxiety = 'Stress & Anxiety';
    public $fatigue = 'Fatigue';
    public $weight = 'Weight Loss/Gain';
    public $generalHealth = 'General Health';

    public function url()
    {
        return '/';
    }



    public function assertCoverTitle(Browser $browser)
    {
      $browser->waitForText($this->cover)
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

  public function clickAssert(Browser $browser, $selector, $assertion)
  {
        $browser->click($selector)
                ->waitForText($this->$assertion)
                ->assertSee($this->$assertion);
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
            '@loginHeader' => '#app > div.nav-wrap.nav-is-mobile.nav-is-sticky > div.nav-container > div > div.nav-links > a:nth-child(3)',
            '@harveyLogoHeader' => '#app > div.nav-wrap.nav-is-mobile.nav-is-sticky > div.nav-container > div > button',
            '@labTesting' => '#tests > div > h2 > span',
            '@about1' => '#app > div.nav-wrap.nav-is-mobile.nav-is-sticky > div.nav-container > div > div.nav-links > a:nth-child(1)',
            '@about2' => '#app > div.page-content > div > section:nth-child(2) > div > div > a',
            '@labsTestHeader' => '#app > div.nav-wrap.nav-is-sticky > div.nav-container > div > div.nav-links > a:nth-child(2)',
            '@pricingHeader' => '#app > div.header.nav.is-inverted > div > div.nav-right > span > a:nth-child(3)',
            '@storiesHeader' => '#app > div.nav-wrap.nav-is-mobile.nav-is-sticky > div.nav-container > div > div.nav-links > a:nth-child(2)',
            '@consultHeader' => '#app > div.nav-wrap.nav-is-mobile.nav-is-sticky > div.nav-container > div > div.nav-right > div.nav-phone > a',
            '@consultCover' => '#hero-background > div.container > div > div > p > a',
            '@shopStoreHeader' => '#app > div.nav-wrap.nav-is-mobile.nav-is-sticky > div.nav-container > div > div.nav-right > div.nav-start > a > i',
            '@exloreConditionsCover' => '#app > div.page-content > div > section.hero.hero-background > div.hero-body > div > div > div > div > a',
            '@startShopping' => '#hero-background > div.container > div > div > a',
            '@bookAppTwo' => '#get-started > div > div > div > a',
            '@labMouseOver' => '#feature > div',
            '@labsTestButton' => '#feature > div > div > div > div > div > a',
            '@shopNowLab' => '#products > div > div > div:nth-child(1) > div.pa2 > a',
            '@shopNowSupplements' => '#products > div > div > div:nth-child(2) > div.pa2 > a',
            '@meetDoctors' => '#products > div > div > div:nth-child(3) > div.pa2 > a',
            '@mouseOverStoryBody' => '#services > div:nth-child(2) > div > div:nth-child(1) > div > i',
            '@startShoppingOverFooter' => '#get-started > div > div > div > a',
            '@consultDoctorOverFooter' => '#get-started > div > div > div > p > a',
            '@storiesBody' => '#services > div:nth-child(1) > div > div > a',
            '@footer' => '#footer > div:nth-child(1)',
            '@homeFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(1) > div > div > div.accordian-wrapper > div > a:nth-child(1)',
            '@aboutFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(1) > div > div > div.accordian-wrapper > div > a:nth-child(2)',
            '@storiesFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(1) > div > div > div.accordian-wrapper > div > a:nth-child(3)',
            '@termsFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(1) > div > div > div.accordian-wrapper > div > a:nth-child(5)',
            '@privacyFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(1) > div > div > div.accordian-wrapper > div > a:nth-child(6)',
            '@helpFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(1) > div > div > div.accordian-wrapper > div > a:nth-child(7)',
            '@skinIssuesFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(2) > div > div > div.accordian-wrapper > div > a:nth-child(1)',
            '@foodAllergyFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(2) > div > div > div.accordian-wrapper > div > a:nth-child(2)',
            '@stressAndAnxietyFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(2) > div > div > div.accordian-wrapper > div > a:nth-child(3)',
            '@fatigueFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(2) > div > div > div.accordian-wrapper > div > a:nth-child(4)',
            '@weightFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(2) > div > div > div.accordian-wrapper > div > a:nth-child(5)',
            '@generalHealthFooter' => '#footer > div:nth-child(1) > div > div.c-x-2-s.c-x-4-m.c-7-4-xl > div > div > div:nth-child(2) > div > div > div.accordian-wrapper > div > a:nth-child(7)'

          ];
    }
}
