<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\ConsultationsPage;

class ConsultationPageTest extends DuskTestCase
{
  use DatabaseMigrations;
  /**
   * A Dusk test example.
   *
   * @return void
   */
  public function test_consultations_page_is_up()
  {
      $this->browse(function (Browser $browser) {
          $browser->visit(new ConsultationsPage)
                  ->assertSee('Book a consultation with');
      });
  }

  // Shop Products
  public function test_skin_issues_button()
  {
      $this->browse(function (Browser $browser) {
          $browser->visit(new ConsultationsPage)
                  ->clickAssert('@skinIssuesShop', 'skinIssues');
      });
  }

  public function test_food_allergies_button()
  {
      $this->browse(function (Browser $browser) {
          $browser->visit(new ConsultationsPage)
                  ->clickAssert('@foodAllergiesShop', 'foodAllergies');
      });
  }

  public function test_stress_and_anxiety_button()
  {
      $this->browse(function (Browser $browser) {
          $browser->visit(new ConsultationsPage)
                  ->clickAssert('@stressAndAnxietyShop', 'stressAndAnxiety');
      });
  }

  public function test_digestive_issues_button()
  {
      $this->browse(function (Browser $browser) {
          $browser->visit(new ConsultationsPage)
                  ->clickAssert('@fatigueShop', 'fatigue');
      });
  }

  public function test_weight_loss_button()
  {
      $this->browse(function (Browser $browser) {
          $browser->visit(new ConsultationsPage)
                  ->clickAssert('@weightShop', 'weight');
      });
  }


    public function test_womans_health_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ConsultationsPage)
                    ->clickAssert('@womansHealthShop', 'womansHealth');
        });
    }

    public function test_general_health_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ConsultationsPage)
                    ->clickAssert('@generalHealthShop', 'generalHealth');
        });
    }

}
