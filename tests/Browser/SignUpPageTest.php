<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\SignUpPage;

class SignUpPageTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */


    public function test_if_error_messages_show_up_correctly()
    {
       $this->browse(function (Browser $browser) {
          $browser->visit(new SignUpPage)
                  ->mandatoryFieldCheck();
          });
    }

    public function test_if_email_is_validated_for_format()
    {
      $this->browse(function (Browser $browser){
         $browser->visit(new SignUpPage)
                 ->emailNotValid();

      });
    }

    public function test_if_password_is_validated_for_being_shorter_then_6_digits()
    {
      $this->browse(function (Browser $browser){
         $browser->visit(new SignUpPage)
                 ->shortPassword();

      });
    }

    public function test_if_user_is_sent_back_to_intial_form_after_zipcode_error_page()
    {
      $this->browse(function (Browser $browser){
         $browser->visit(new SignUpPage)
                 ->wrongZip();

      });
    }


}
