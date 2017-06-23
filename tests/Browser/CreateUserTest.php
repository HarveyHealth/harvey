<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\Browser\Pages\SignUpPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateUserTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_page_load()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new SignUpPage)
                    ->waitForText('Your journey starts here')
                    ->assertSee('Your journey starts here');
        });
    }


    public function test_user_is_created_and_sent_to_next_page()
    {
        $user = factory(User::class)->make();


          $this->browse(function ($browser) use ($user) {
              $browser->visit(new SignUpPage)
                      ->addUser($user);

                    });

              $this->assertDatabaseHas('users', ['email' => $user->email]);
    }


}
