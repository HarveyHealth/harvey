<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\LabTestPage;

class labTestsPageTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */


    public function test_if_micronutrient_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testMicronutrients();
        });
    }

    public function test_if_hormones_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testHormones();
        });
    }

    public function test_if_adrenals_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testAdrenals();
        });
    }

    public function test_if_thyroids_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testThyroid();
        });
    }

    public function test_if_cardio_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testCardio();
        });
    }

    public function test_if_cbc_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testCBC();
        });
    }

    public function test_if_toxic_metals_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testToxicMetals();
        });
    }

    public function test_if_toxic_chemicals_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testToxicChemicals();
        });
    }

    public function test_if_food_allergies_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testFoodAllergies();
        });
    }

    public function test_if_microbiome_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testMicrobiome();
        });
    }

    public function test_if_organic_tab_works()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->testOrganic();
        });
    }

    public function test_if_book_now_button()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LabTestPage)
                    ->bookNow();
        });
    }
}
