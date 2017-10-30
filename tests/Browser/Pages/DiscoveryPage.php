<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use App\Models\{User, License};

class DiscoveryPage extends BasePage
{   public $signUpPage = "Based on your answers, we're confident our Naturopathic Doctors can help improve your health condition!";
    public $noService = "Unfortunately, we cannot service patients in your state yet.";
    public $service = "Now serving patients";

    public $regulatedNoStates = [
      '35004', '35005', '35019', '35049' , '35079', '35147', '35208', //Alabama
      '32004', '32008', '32035', '32056', '32068' , '32083', '32868', //Florida
      '00501', '10028', '10108', '10601', '11360', '11940', '12220', //New York
      '29001', '29305', '29592', '29838', '29925', '29940', '29936', //South Carolina
      '37317', '37011', '37018', '37042', '37086', '37246', '37359' //Tenesee
    ];

    public $regulatedYesStates = [
      'AK' => ['99501', '99523', '99565', '99621', '99676', '99706', '99777'], //Alaska
      'CA' => ['90001', '90312', '90623', '90809', '91203', '91340', '91521', '90405'], //California
      'HI' => ['96701', '96707', '96748', '96809', '96849', '96854', '96861'], //Hawaii
      'OR' => ['97001', '97064', '97114', '97141', '97228', '97304', '97389'], //Oregon
      'WA' => ['98001', '98024', '98056', '98115', '98178', '98248', '98355'], //Washington
      'AZ' => ['85001', '85023', '85061', '85190', '85274', '85338', '85383'], //Arizona
      'CO' => ['80001', '80036', '80110', '80201', '80229', '80419', '80553'], //Colorado
      'MT' => ['59001', '59013', '59030', '59067', '59221', '59402', '59468'], //Montana
      'UT' => ['84001', '84034', '84062', '84096', '84145', '84315', '84539'], //Utah
      'KS' => ['66002', '66016', '66045', '66086', '66205', '66425', '66611'], //Kansas
      'MN' => ['55001', '55040', '55085', '55120', '55304', '55343', '55386'], //Minnesota
      'ND' => ['58001', '58046', '58109', '58237', '58272', '58338', '58416'], //North Dakota
      'CT' => ['06001', '06023', '06067', '06131', '06249', '06360', '06437'], //Connecticut
      'ME' => ['03901', '04004', '04039', '04084', '04222', '04285', '04401'], //Maine
      'MD' => ['20588', '20620', '20660', '20697', '20741', '20788', '20861'], //MaryLand
      'MA' => ['01001', '01011', '01061', '01098', '01222', '01342', '01560'], //Massachusetts
      'NH' => ['03031', '03063', '03224', '03258', '03304', '03586', '03771'], //New Hampshire
      'PA' => ['15001', '15034', '15071', '15076', '15147', '15258', '15359'], //Pennsylvania
      'VT' => ['05001', '05047', '05101', '05344', '05453', '05655', '05738'], //Vermont
      'DC' => ['20001', '20030', '20066', '20207', '20251', '20301', '20434'] //Washington DC
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

    public function regulatedNoStates(Browser $browser)
    {
      $zip = $browser->zipRand($this->regulatedNoStates);

      $browser->toZipForm()
              ->type('@box1', $zip[0])
              ->type('@box2', $zip[1])
              ->type('@box3', $zip[2])
              ->type('@box4', $zip[3])
              ->type('@box5', $zip[4])
              ->click('@verify')
              ->waitForText($this->noService)
              ->assertSee($this->noService);
    }

    public function addUserRegulatedStatesSuccess(Browser $browser, $user)
            {
            $state = array_rand($this->regulatedYesStates);
            $LicenceState = factory(License::class)->create([
              'state' => $state
            ]);
            // die($LicenceState);

            $zip = $browser->zipRand($this->regulatedYesStates[$state]);

            $browser->toZipForm()
                    ->type('@box1', $zip[0])
                    ->type('@box2', $zip[1])
                    ->type('@box3', $zip[2])
                    ->type('@box4', $zip[3])
                    ->type('@box5', $zip[4])
                    ->click('@verify')
                    ->waitForText($this->service)
                    ->assertSee($this->service);


          }

      public function addUserRegulatedStatesFail(Browser $browser, $user)
              {
                $state = array_rand($this->regulatedYesStates);
                $zip = $browser->zipRand($this->regulatedYesStates[$state]);

                $browser->toZipForm()
                        ->type('@box1', $zip[0])
                        ->type('@box2', $zip[1])
                        ->type('@box3', $zip[2])
                        ->type('@box4', $zip[3])
                        ->type('@box5', $zip[4])
                        ->click('@verify')
                        ->waitForText($this->noService)
                        ->assertSee($this->noService);


              }


        public function addUserUnregulatedStates(Browser $browser, $user)
              {
                $id = array_rand($this->unRegulatedStates, 1);
                $browser->toZipForm()
                        ->type('@first_name', $user->first_name)
                        ->type('last_name' , $user->last_name)
                        ->type('email' , $user->email)
                        ->type('password', bcrypt('secret'))
                        ->checkTerms()
                        ->clickSignUp()
                        ->waitForText('Welcome to Harvey')
                        ->assertSee('Welcome to Harvey');

                      }



        public function toZipForm(Browser $browser)
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
