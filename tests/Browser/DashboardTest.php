<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Tests\Browser\Pages\Dashboard;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DashboardTest extends DuskTestCase
{
    use DatabaseMigrations;
    
    public function testExample()
    {
        $patient = factory(User::class)
            ->states('patient')
            ->create();
        
        $this->browse(function ($browser) use ($patient) {
            $browser->loginAs($patient)
                    ->visit(new Dashboard)
                    ->logout()
                    ->assertPathIs('/');
        });
    }
}
