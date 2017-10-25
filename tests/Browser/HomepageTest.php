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


    public function test_about_button_in_header()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->aboutHeader();
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

    public function test_book_appointment_in_cover()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->bookCover();
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
                    ->homeFooter();
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
                    ->labsFooter();
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
                      ->helpFooter();
          });
       }

     public function test_if_terms_button_work_in_footer()
     {
        $this->browse(function ($browser) {
             $browser->visit(new HomePage)
                     ->script('window.scrollTo(0,document.body.scrollHeight)');
     });
         $this->browse(function ($browser) {
             $browser->visit(new HomePage)
                     ->termsFooter();
         });
      }

      public function test_if_privacy_button_work_in_footer()
      {
          $this->browse(function ($browser) {
              $browser->visit(new HomePage)
                      ->script('window.scrollTo(0,document.body.scrollHeight)');
      });
          $this->browse(function ($browser) {
              $browser->visit(new HomePage)
                      ->privacyFooter();
          });
       }

}
