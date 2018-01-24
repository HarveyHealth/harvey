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
                    ->clickAssert('@about1', 'about');
          });
    }


    public function test_login_button_in_header()
    {
        $this->browse(function ($browser){
            $browser->visit(new HomePage)
                    ->clickAssert('@loginHeader', 'login');
        });

    }

    public function test_stories_button_in_header()
    {
        $this->browse(function ($browser){
            $browser->visit(new HomePage)
                    ->clickAssert('@storiesHeader', 'stories');
        });
    }

    public function test_consult_button_in_header()
    {
        $this->browse(function ($browser) {
           $browser->visit(new HomePage)
                   ->clickAssert('@consultHeader', 'consultations');
        });
    }


   //Cover Image tests

    public function test_start_shopping_button_on_cover()
    {
        $this->browse(function ($browser) {
           $browser->visit(new HomePage)
                   ->clickAssert('@startShopping', 'shopify');
        });
    }

    public function test_consult_a_doctor_on_cover()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->clickAssert('@consultCover', 'consultations');
        });
    }


    // public function test_if_home_button_works_in_footer()
    // {
    //     $this->browse(function ($browser) {
    //         $browser->visit(new HomePage)
    //                 ->script('window.scrollTo(0,document.body.scrollHeight)');
    // });
    //     $this->browse(function ($browser) {
    //         $browser->visit(new HomePage)
    //                 ->footer('cover');
    //     });
    //  }
    //
    //
    // public function test_if_labs_button_work_in_footer()
    // {
    //     $this->browse(function ($browser) {
    //         $browser->visit(new HomePage)
    //                 ->script('window.scrollTo(0,document.body.scrollHeight)');
    // });
    //     $this->browse(function ($browser) {
    //         $browser->visit(new HomePage)
    //                 ->footer('labs');
    //     });
    //  }
    //
    //  public function test_if_financing_button_work_in_footer()
    //  {
    //     $this->browse(function ($browser) {
    //          $browser->visit(new HomePage)
    //                  ->script('window.scrollTo(0,document.body.scrollHeight)');
    //  });
    //      $this->browse(function ($browser) {
    //          $browser->visit(new HomePage)
    //                  ->footer('financing');
    //      });
    //   }
    //
    //   public function test_if_help_button_works_in_footer()
    //   {
    //       $this->browse(function ($browser) {
    //           $browser->visit(new HomePage)
    //                   ->script('window.scrollTo(0,document.body.scrollHeight)');
    //   });
    //       $this->browse(function ($browser) {
    //           $browser->visit(new HomePage)
    //                   ->footer('help');
    //       });
    //    }





}
