<?php

namespace Tests\Browser;



use Tests\DuskTestCase;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\Header;
use Illuminate\Foundation\Testing\DatabaseMigrations;




class HomepageTest extends DuskTestCase
{
    public function test_if_homepage_is_up()
    {
        $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->assertSee('Mind. Body. Spirit.');
        });
    }
//Tests Header
    public function test_get_started_button_in_header()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->getStartedHeader(new HomePage);
              });
    }

    public function test_login_button()
    {
        $this->browse(function ($browser){
            $browser->visit(new HomePage)
                    ->loginHeader();
        });

    }

    public function test_logo_in_header()
    {
        $this->browse(function ($browser){
            $browser->visit(new HomePage)
                ->logoHeader();
        });

    }



    public function test_first_get_started_button_on_page()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->getStartedOne();
              });
    }

    public function test_second_get_started_button_on_page()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->getStartedTwo();
              });
    }

    // public function test_if_footer_links_work()
    // {
    //     $this->browse(function ($browser) {
    //         $browser->visit(new HomePage)
    //                 ->assertSee('It’s time to think differently about your medicine.');
    //               // ->mouseover('.footer')
    //               // ->clickLink('Terms')
    //               // ->assertSee('Welcome to Harvey (“Harvey” or “Company”).')
    //               // ->visit('/')
    //               // ->mouseover('.footer')
    //               // ->clickLink('Privacy')
    //               // ->assertSee('This Privacy Policy describes the information Harvey (“Harvey”) collects about you,')
    //               // ->visit('/')
    //               // ->mouseOver('.footer')
    //               // ->clickLink('Book')
    //               // ->assertSee('Create your account')
    //               // ->visit('/')
    //               // ->mouseOver('.footer')
    //               // ->clickLink('Labs')
    //               // ->assertSee('Lab Tests & Pricing');
    //     });
    // }

    //
    // public function test_explore_other_tests_button()
    // {
    //       $this->browse(function ($browser){
    //         $browser->visit(new HomePage)
    //                 ->mouseOver('@mouseOverHowitWorks')
    //                 ->click('@exploreOtherTests')
    //                 ->assertSee('Lab Tests & Pricing');
        //   });
        //
        // }
    }
