<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\Header;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomepageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_if_homepage_is_up()
    {
        $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->assertCoverTitle();
        });
    }

    //####### Header Tests
    public function test_about_buttons()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->clickAbout('1')
                    ->clickAbout('2');
          });
    }

    public function test_lab_tests_button_in_header()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->labsTestHeader();
          });
    }


    public function test_get_started_in_header()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->getStartedHeader();
              });
    }

    public function test_login_button_in_header()
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

    // public function test_financing_in_header()
    // {
    //
    //   $this->browse(function ($browser){
    //       $browser->visit(new Homepage)
    //               ->financingHeader();
    //   });
    //
    // }

    //Cover Image tests

    // public function test_book_appointment_in_cover()
    // {
    //       $this->browse(function ($browser) {
    //         $browser->visit(new HomePage)
    //                 ->bookCover();
    //           });
    // }

    public function test_explore_conditions_in_cover()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->exploreConditionsCover();
              });
    }

    public function test_if_labs_tests_button_works()
    {   $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->script('window.scrollTo(0,2000)');
    });
        $this->browse(function ($browser) {

            $browser->visit(new HomePage)
                    ->labsButton();
        });
    }

    public function test_if_home_button_works_in_footer()
    {
        $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->script('window.scrollTo(0,document.body.scrollHeight)');
    });
        $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->footer('cover');
        });
     }


    public function test_if_labs_button_work_in_footer()
    {
        $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->script('window.scrollTo(0,document.body.scrollHeight)');
    });
        $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->footer('labs');
        });
     }

     public function test_if_financing_button_work_in_footer()
     {
        $this->browse(function ($browser) {
             $browser->visit(new HomePage)
                     ->script('window.scrollTo(0,document.body.scrollHeight)');
     });
         $this->browse(function ($browser) {
             $browser->visit(new HomePage)
                     ->footer('financing');
         });
      }

      public function test_if_help_button_works_in_footer()
      {
          $this->browse(function ($browser) {
              $browser->visit(new HomePage)
                      ->script('window.scrollTo(0,document.body.scrollHeight)');
      });
          $this->browse(function ($browser) {
              $browser->visit(new HomePage)
                      ->footer('help');
          });
       }





}
