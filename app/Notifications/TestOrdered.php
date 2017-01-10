<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TestOrdered extends Notification
{
    use Queueable;

    public $test;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($test)
    {
        $this->test = $test;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable) {

        $patient = $this->test->patient;

        $address_array = [$patient->address_1];
        if (!empty($patient->address_2))
            $address_array[] = $patent->address_2;
        $address_array[] = $patent->city . ', ' . $patient->state . ' ' . $patent->zip;

        $address = implode("\n", $address_array);

        return (new SlackMessage)
            ->success()
            ->content('A test has been ordered!')
            ->attachment(function ($attachment) use ($url) {
                $attachment->title($this->test->name)
                           ->fields([
                                'Patient' => $patient->fullName(),
                                'Practitioner' => $test->practitioner->fullName(),
                                'Address' => $address
                            ]);
            });
    }
}
