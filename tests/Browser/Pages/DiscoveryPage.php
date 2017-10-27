<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class DiscoveryPage extends BasePage
{   public $signUpPage = "Based on your answers, we're confident our Naturopathic Doctors can help improve your health condition!";
    public $noService = "Unfortunately, we cannot service patients in your state yet.";
    public $regulatedNoStates = [
      '35004', '35005', '35019', '35049' , '35079', '35147', '35208', //Alabama
      '32004', '32008', '32035', '32056', '32068' , '32083', '32868', //Florida
      '00501', '10028', '10108', '10601', '11360', '11940', '12220', //New York
      '29001', '29305', '29592', '29838', '29925', '29940', '29936', //South Carolina
      '37317', '37011', '37018', '37042', '37086', '37246', '37359' //Tenesee
    ];


    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/conditions';
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

    //**** Below functions test zipcode location logic
    public function regulatedNoStates(Browser $browser)
    {
      $browser->toZip()
              ->type('@box1', '3') //32035
              ->type('@box2', '2')
              ->type('@box3', '0')
              ->type('@box4', '3')
              ->type('@box5', '5')
              ->click('@verify')
              ->waitForText($this->noService)
              ->assertSee($this->noService);
    }


        public function toZip(Browser $browser)
        {
          $browser->click('@allergies')
                  ->press('Continue')
                  ->waitFor('@q1Food')
                  ->click('@q1Food')
                  ->pause(1200)
                  ->waitFor('@q2PastMonth')
                  ->click('@q2PastMonth')
                  ->pause(1200)
                  ->waitFor('@q3Yes')
                  ->click('@q3Yes')
                  ->pause(1200)
                  ->waitFor('@q4Daily')
                  ->click('@q4Daily')
                  ->pause(1200)
                  ->waitFor('@q5Yes')
                  ->click('@q5Yes')
                  ->waitFor('@box1');
        }

        //***** Tests below check the different discovery flows

        public function allergies(Browser $browser)
        {
          $browser->click('@allergies')
                  ->press('Continue')
                  ->waitFor('@q1Food')
                  ->assertSee('What are your allergies caused by?')
                  ->click('@q1Food')
                  ->pause(1200)
                  ->waitFor('@q2PastMonth')
                  ->click('@q2PastMonth')
                  ->pause(1200)
                  ->waitFor('@q3Yes')
                  ->click('@q3Yes')
                  ->pause(1200)
                  ->waitFor('@q4Daily')
                  ->click('@q4Daily')
                  ->pause(1200)
                  ->waitFor('@q5Yes')
                  ->click('@q5Yes')
                  ->waitForText('What is your zip code?')
                  ->assertSee('What is your zip code?');
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
            //Allergies
            '@allergies' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-xl.pad-md.color-white > div.space-top-lg.is-padding-lg.Row > div:nth-child(1) > a > div',
            '@contiuneAllergies' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div.space-children-xl > div > button',
            '@q1Food' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div:nth-child(3) > div.space-top-lg.is-padding-lg.Row.gutter-md > div:nth-child(1) > div',
            '@q2PastMonth' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div:nth-child(3) > div.space-top-lg.is-padding-lg.Row.gutter-md > div:nth-child(1) > div',
            '@q3Yes' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div:nth-child(3) > div.space-top-lg.is-padding-lg.Row.gutter-md > div:nth-child(1) > div',
            '@q4Daily' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div:nth-child(3) > div.space-top-lg.is-padding-lg.Row.gutter-md > div:nth-child(1) > div',
            '@q5Yes' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div:nth-child(3) > div.space-top-lg.is-padding-lg.Row.gutter-md > div:nth-child(3) > div',
            //zipcode fields
            '@box1' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div > div > input[type="text"]:nth-child(1)',
            '@box2' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div > div > input[type="text"]:nth-child(2)',
            '@box3' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div > div > input[type="text"]:nth-child(3)',
            '@box4' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div > div > input[type="text"]:nth-child(4)',
            '@box5' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div > div > input[type="text"]:nth-child(5)',
            '@verify' => '#app > div > div:nth-child(2) > div.margin-0a.max-width-lg.pad-md.color-white > div > div > button'

        ];
    }
}
