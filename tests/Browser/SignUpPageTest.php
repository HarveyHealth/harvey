<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\SignUpPage;

class LoginPageTest extends DuskTestCase
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

    public function test_if_pass_is_validated_for_being_shorter_then_6_digits()
    {
      $this->browse(function (Browser $browser){
         $browser->visit(new SignUpPage)
                 ->shortPassword();

      });
    }



}
