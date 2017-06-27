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
                    ->assertCoverTitle();
        });
    }


    public function test_if_book_now_is_in_header()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->bookNowHeader();
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

    public function test_first_book_appointment_on_page()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->bookAppOne();
              });
    }

    public function test_second_book_appointment_button_on_page()
    {
          $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->bookAppTwo();
              });
    }

    public function test_if_labs_tests_button_works()
    {
        $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->labsButton();
        });
    }

    public function test_if_home_button_works_in_footer()
    {
        $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->homeFooter();
        });
     }


    public function test_if_labs_button_work_in_footer()
    {
        $this->browse(function ($browser) {
            $browser->visit(new HomePage)
                    ->labsFooter();
        });
     }

    //  public function test_if_blog_button_works_in_footer()
    //  {
    //      $this->browse(function ($browser) {
    //          $browser->visit(new HomePage)
    //                  ->blogFooter();
    //      });
    //   }

      public function test_if_FAQ_button_works_in_footer()
      {
          $this->browse(function ($browser) {
              $browser->visit(new HomePage)
                      ->faqFooter();
          });
       }

     public function test_if_terms_button_work_in_footer()
     {
         $this->browse(function ($browser) {
             $browser->visit(new HomePage)
                     ->termsFooter();
         });
      }

      public function test_if_privacy_button_work_in_footer()
      {
          $this->browse(function ($browser) {
              $browser->visit(new HomePage)
                      ->privacyFooter();
          });
       }

}
