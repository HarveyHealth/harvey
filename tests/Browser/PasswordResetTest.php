<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PasswordResetTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_a_user_can_request_a_password_reset_link()
    {
        $user = factory(User::class)->create();

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/password/reset')
                ->type('email', $user->email)
                ->press('Send Password Reset Link')
                ->waitForText("We can't find a user with that e-mail address.")
                ->assertSee("We can't find a user with that e-mail address.");
        });
    }
}
