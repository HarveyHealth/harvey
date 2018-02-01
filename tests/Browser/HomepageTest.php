<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Components\Footer;
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

    public function test_shop_store_button_in_header()
    {
        $this->browse(function ($browser) {
           $browser->visit(new HomePage)
                   ->clickAssert('@shopStoreHeader', 'shopify');
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

    // Second set of button Home Lab Tests - Vitamins & Supplements - Holistic Treatment Plans

    public function test_shopnow_lab_button()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->clickAssert('@shopNowLab', 'shopifyLabs');
        });
    }

    public function test_shopnow_supplements_button()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->clickAssert('@shopNowSupplements', 'shopifySupplements');
        });
    }

    public function test_meet_doctors_button()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->clickAssert('@meetDoctors', 'consultations');
        });
    }

    public function test_watch_patient_stories_button()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@mouseOverStoryBody')
                   ->clickAssert('@storiesBody', 'stories');
        });
    }

    public function test_shop_now_over_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@startShoppingOverFooter', 'shopify');
        });
    }

    public function test_home_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@homeFooter', 'cover');
        });
    }

    public function test_about_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@aboutFooter', 'about');
        });
    }

    public function test_stories_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@storiesFooter', 'stories');
        });
    }

    public function test_terms_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@termsFooter', 'terms');
        });
    }

    public function test_privacy_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@privacyFooter', 'privacy');
        });
    }

    public function test_help_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@helpFooter', 'help');
        });
    }

    public function test_skin_issues_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@skinIssuesFooter', 'skinIssues');
        });
    }

    public function test_food_allergy_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@foodAllergyFooter', 'foodAllergy');
        });
    }

    public function test_stress_and_anxiety_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@stressAndAnxietyFooter', 'stressAndAnxiety');
        });
    }

    public function test_weight_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@weightFooter', 'weight');
        });
    }

    public function test_general_health_in_footer()
    {
        $this->browse(function($browser) {
           $browser->visit(new Homepage)
                   ->mouseOver('@footer')
                   ->clickAssert('@generalHealthFooter', 'generalHealth');
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
