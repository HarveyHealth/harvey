<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Notifications\Messages\SlackMessage;

class PaymentFailed extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        $data = $notifiable->data;

        return (new SlackMessage)
                ->error()
                ->content('Payment Failed')
                ->attachment(function ($attachment) use ($data) {
                    $attachment->title($data['user']->fullName(), config('app.url') . '/users/' . $data['user']->id)
                               ->fields([
                                    'Title' => 'Amount',
                                    'Amount' => number_format($data['amount'] / 100, 2),
                                    'Via' => 'Stripe Txn ID',
                                    'Was Overdue' => $data['stripe_transaction_id'],
                                ]);
                });
    }
}
