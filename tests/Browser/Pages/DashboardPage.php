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
            '@appointment' => '#app > div.main-container.is-patient > div > div:nth-child(2) > div.card.card-appointments > div > div.card-content-container > div.appointment-wrapper > div > div.appointment_right > div > a'
        ];
    }

    public function logout(Browser $browser)
    {
        $browser->press('@accountButton')
            ->clickLink('Logout');
    }
}
