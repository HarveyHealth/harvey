<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\ForgotPasswordPage;

class PasswordResetTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_a_user_can_request_a_password_reset_link()
    {
        $user = factory(User::class)->create();

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new ForgotPasswordPage)
                ->waitForText('Enter your email address below and we will send you a link to reset your password.')
                ->type('email', $user['email'])
                ->press('Send Reset Link')
                ->waitForText('We have e-mailed your password reset link!')
                ->assertSee("We have e-mailed your password reset link!");
        });
    }
}
