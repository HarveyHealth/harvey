<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\AboutPage;

class AboutPageTests extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_if_about_page_is_up()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AboutPage)
                    ->assertSee('About Harvey');
        });
    }

    public function test_food_sensitivities_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AboutPage)
                    ->mouseOver('@footer')
                    ->clickAssert('@foodSensitivities', 'foodSens');
        });
    }

    public function test_micornutrient_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AboutPage)
                    ->mouseOver('@footer')
                    ->clickAssert('@micronutrient', 'microNutrient');
        });
    }

    public function test_microbiome_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AboutPage)
                    ->mouseOver('@footer')
                    ->clickAssert('@microbiome', 'microbiome');
        });
    }

    public function test_lab_test_shop_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AboutPage)
                    ->mouseOver('@footer')
                    ->clickAssert('@supplementsShop', 'supplements');
        });
    }

    public function test_meet_doctors_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new AboutPage)
                    ->mouseOver('@footer')
                    ->clickAssert('@supplementsShop', 'supplements');
        });
    }
}
