<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class DashboardPage extends BasePage
{
    public function url()
    {
        return '/dashboard';
    }

    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    public function elements()
    {
        return [
            '@element' => '#selector',
            '@accountButton' => 'i.fa.fa-user-circle-o',
            '@newAppointmentButton' => '.nav-item a.button.is-primary',
            '@lastDate' => '#app > div > div.container > form > div:nth-child(1) > ul > li:nth-child(7)',
            '@lastTime' => '#app > div > div.container > form > div:nth-child(2) > ul > li:nth-child(9)',
            '@maleGender' => '#app > div > div.container > form > div > div:nth-child(5) > div.is-expanded > div > p:nth-child(1) > label',
            '@submit' => '#app > div > div.container > div.is-clearfix.hero-buttons > button',
            '@successFlashElement' => '#app > div > div.notification.is-success',
            '@footer' => '#app > footer',
            '@appointment' => '#app > div.main-container.is-patient > div > div:nth-child(2) > div.card.card-appointments > div > div.card-content-container > div.appointment-wrapper > div > div.appointment_right > div > a',
            '@messagesTab' => '#app > div.nav-bar > nav > a:nth-child(5)',
            '@newMessage' => '#app > div.main-container > div.main-content > div.main-header > div > h1 > button',
            '@MessageDrop' => '#app > div.main-container > div.main-content > div.flyout.isactive > aside > div:nth-child(3) > span',
            '@appointmentTab' => '#app > div.nav-bar > nav > a:nth-child(3)',
            '@appointmentRow' => '#app > div.main-container > div.main-content > table > tbody > tr:nth-child(3)',
            '@labOrderTab' => '#app > div.nav-bar > nav > a:nth-child(4)',
            '@labOrderRow' => '#app > div.main-container > div.main-content > table > tbody > tr:nth-child(3)',
            '@profileTab' => '#app > div.nav-bar > nav > a:nth-child(6)',
            '@clientsTab' => '#app > div.nav-bar > nav > a:nth-child(6)',
            '@profileSave'=> '#user_form > div.submit.inline-centered > button',
            '@firstName' => '#user_form > div.formgroups > div:nth-child(1) > div.input__container.input-wrap > input',
            "@adminAppoint" => '#app > div.nav-bar > nav > a:nth-child(3)',
            '@adminLabs' => '#app > div.nav-bar > nav > a:nth-child(4)',
            '@adminMessages' => '#app > div.nav-bar > nav > a:nth-child(5)',
            '@adminClients' => '#app > div.nav-bar > nav > a:nth-child(6)',
            '@adminProfile' => '#app > div.nav-bar > nav > a:nth-child(7)'

        ];
    }

    public function logout(Browser $browser)
    {
        $browser->press('@accountButton')
            ->clickLink('Logout');
    }
}
