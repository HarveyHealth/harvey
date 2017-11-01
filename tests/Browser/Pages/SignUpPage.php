<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use App\Models\{User, License, Practitioner};
use Illuminate\Support\Facades\DB;
use Tests\Browser\Pages\DiscoveryPage;


class SignUpPage extends BasePage
{


    public $errorMessages = [
      'first_name' => 'First name is required',
      'last_name' => 'Last name is required',
      'email' => 'Email is required' ,
      'emailnotvalid' => 'Not a valid email address',
      'zipcode' => 'Zipcode is required',
      'nopass' => 'Password is required' ,
      'passshort' => 'Password needs minimum of 6 characters'

    ];



    public $unRegulatedStates = [
      '71601', '71744', '71768', '71865', '71971', '72078', '72320', //Arkansas
      '19701', '19726', '19904', '19964', '19971', '19975', '19980', //Delaware
      '30002', '30014', '30090', '30113', '30141', '30182', '30253', //Georgia
      '83201', '83232', '83278', '83348', '83454', '83631', '83707', //Idaho
      '60001', '60015', '60037', '60056', '60094', '60145', '60442', //Illinoise
      '46001', '46107', '46175', '46217', '46352', '46561', '46778', //Indiana
      '50001', '50044', '50111', '50160', '50264', '50428', '50681', //Iowa
      '40003', '40063', '40078', '40250', '40355', '40514' ,'40769', //Kentucky
      '70001', '70058', '70118', '70195', '70447', '70544', '70726', //Louisiana
      '48001', '48034', '48086', '48136', '48185', '48260', '48366', //Michigan
      '38601', '38631', '38702', '38760', '38850', '38924', '39043', //Mississippi
      '63001', '63043', '63105', '63163', '63353', '63447', '63674', //Missouri
      '68001', '68034', '68108', '68180', '68347', '68417', '68467', //Nebraska
      '88901', '89007', '89104', '89161', '89701', '89830', '89883', //Nevada
      '07001', '07011', '07057', '07106', '07410', '07511', '07677', //new Jersey
      '87001', '87044', '87109', '87199', '87507', '87554', '87940', //New Mexico
      '27006', '27055', '27115', '27217', '27312', '27417', '27562', //North Carolina
      '43001', '43018', '43068', '43085', '43143', '43223', '43357', '43230', //Ohio
      '73001', '73024', '73068', '73109', '73190', '73501', '73626', //Oklahoma
      '02801', '02873', '02909', '02911', '02912', '02918', '02920', //Rhode Island
      '57001', '57018', '57057', '57110', '57238', '57311', '57350', //South Dakota
      '73301', '75024', '75052', '75075', '75115', '75148', '75185', //Texas
      '20101', '20108', '20151', '20195', '22119', '22206', '22242', //Virginia
      '24701', '24817', '24860', '24934', '25002', '25033', '25110', //West Virginia
      '53001', '53046', '53083', '53129', '53171', '53209', '53293', //Wisconsin
      '82001', '82008', '82210', '82501', '82710', '83002', '83118' //Wyoming

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

    public $randomStates = [
      '71601', '71744', '71768',
      '58001', '58046', '58109',
      '20588', '20620', '20660',
      '82008', '82210', '82501',
      '29305', '29592', '29838',
      '37018', '37042', '37086',
      '27055', '27115', '27217'
    ];






    public function url()
    {
        return '/conditions';
    }


    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }


    public function wrongZip(Browser $browser)
    {
        $browser->type('first_name', 'Alex')
                ->type('last_name', "vaz")
                ->type('email', 'Alex')
                ->type('zip', '99999')
                ->type('password', 'secret')
                ->check('terms')
                ->press('Sign Up');

    }

    public function clickSignUp(Browser $browser)
    {
      $browser->waitFor('@signUp')
              ->click('@signUp');
    }

    public function clickTerms(Browser $browser)
    {
      $browser->click('@terms')
              ->assertSee('Terms and Conditions');
    }

    public function clickPrivacy(Browser $browser)
    {
      $browser->click('@privacy')
              ->assertSee('Please Read Carefully');
    }

    public function checkTerms(Browser $browser)
    {
      $browser->check('terms');
    }

    public function addUser(Browser $browser, $user)
    {
      $browser->waitFor('@first_name')
              ->type('@first_name', $user->first_name)
              ->type('last_name' , $user->last_name)
              ->type('email' , $user->email)
              ->type('zip', '91202')
              ->type('password', bcrypt('secret'))
              ->checkTerms()
              ->clickSignUp();


    }


    public function arrayPractitioners()
    {

      $userUnregulatedState = factory(User::class)->create([
        'zip'=> array_rand($this->randomStates)
      ]);

      $practitionerUnregulatedTwo = factory(Practitioner::class)->create([
        'user_id' => '1'
      ]);

      $userRegulatedYes = factory(User::class)->create([
        'zip'=> array_rand($this->regulatedYesStates)
      ]);

      $practitionerRegulatedYes = factory(Practitioner::class)->create([
        'user_id' => '2'
      ]);

      $userRegulatedYesTwo = factory(User::class)->create([
        'zip'=> array_rand($this->regulatedYesStates)
      ]);

      $practitionerRegulatedYesTwo = factory(Practitioner::class)->create([
        'user_id' => '3'
      ]);

    }

    public function preRegulatedNoFlow(Browser $browser)
    {
      $licenceState = factory(License::class)->create([
        'state' => 'MT'
      ]);

      factory(Practitioner::class)->create([
        'user_id' => $licenceState->user_id
      ]);

    }

    public function addUserRegulatedState(Browser $browser, $user)
          {
            $licenceState = factory(License::class)->create([
              'state' => array_rand($this->regulatedYesStates)
            ]);

            $practitioner = factory(Practitioner::class)->create([
              'user_id' => $licenceState->user_id
            ]);


            $first_name =DB::table('users')->where('id', $practitioner->user_id)->value('first_name');
            $last_name = DB::table('users')->where('id', $practitioner->user_id)->value('last_name');
            $full_name = "Dr. " . $first_name . ' ' . $last_name;


            $browser->waitFor('@first_name')
                    ->type('@first_name', $user->first_name)
                    ->type('last_name' , $user->last_name)
                    ->type('email' , $user->email)
                    ->type('zip', $this->regulatedYesStates[$licenceState->state])
                    ->type('password', bcrypt('secret'))
                    ->checkTerms()
                    ->clickSignUp()
                    ->waitForText('Welcome to Harvey')
                    ->assertSee('Welcome to Harvey')
                    ->click('@letsGo')
                    ->waitForText($full_name)
                    ->assertSee($full_name);
              }

              public function addUserNo(Browser $browser, $user)
                    {
                      $browser->waitFor('@first_name')
                              ->type('@first_name', $user->first_name)
                              ->type('last_name' , $user->last_name)
                              ->type('email' , $user->email)
                              ->type('zip', '91202')
                              ->type('password', bcrypt('secret'))
                              ->checkTerms()
                              ->clickSignUp()
                              ->waitForText('Welcome to Harvey')
                              ->assertSee('Welcome to Harvey');
                        }







    public function emailNotValid(Browser $browser)
    {
      $browser->type('email', 'qwe')
              ->click('@password')
              ->assertSee($this->errorMessages['emailnotvalid']);
    }

    public function shortPassword(Browser $browser)
    {
      $browser->type('password', 'asdf')
              ->click('@last_name')
              ->assertSee($this->errorMessages['passshort']);
    }

    public function elements()
    {
        return [
            '@element' => '#selector',
            '@signUp' => '#app > div > div.height-100 > form > div > div > div > div.font-centered > button:nth-child(1)',
            '@continue' => '#app > div > div > div > button',
            '@practitioner' => '#app > div > div > div.signup-container.signup-stage-container > div.signup-practitioner-wrapper.cf > div:nth-child(1)',
            '@continuePract' => '#app > div > div > div.signup-container.signup-stage-container > div.text-centered > button',
            '@phone_number' => '#app > div > div > div.signup-container.signup-phone-container.text-centered > div:nth-child(2) > div.input-wrap > input',
            '@sendText' => '#app > div > div > div.signup-container.signup-phone-container.text-centered > div:nth-child(2) > button',
            '@first_name' => '#app > div > div.height-100 > form > div > div > div > div:nth-child(2) > input',
            '@last_name' => '#app > div > div.height-100 > form > div > div > div > div:nth-child(3) > input',
            '@email' => '#app > div > div.height-100 > form > div > div > div > div:nth-child(4) > input',
            '@password' => '#app > div > div.height-100 > form > div > div > div > div:nth-child(5) > input',
            '@terms&conditions' => '#checkbox',
            '@letsGo' => '#app > div > div.height-100 > form > div > div > div > div.font-centered > button:nth-child(1)',
            '#practitionerSelectOne' => '#app > div > div > div.signup-container.signup-stage-container > div.signup-practitioner-wrapper.cf > div:nth-child(1) > div:nth-child(3) > button'

        ];
    }


}
