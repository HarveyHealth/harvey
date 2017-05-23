<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Postmark\PostmarkClient;
use Postmark\Models\PostmarkException;

class SendTransactionalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $template;
    protected $template_model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $template, $template_model)
    {
        $this->to = $to;
        $this->template = $template;
        $this->template_model = $template_model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // process the template to get the id

        // remove the base config path if it's there
        $template = str_replace('services.postmark.templates.', '', $this->template);

        $template_id = config('services.postmark.templates.' . $template);

        if (!app()->environment(['production','staging'])) {
            \Log::info("Faking sending transactional email with Postmark to {$this->to} with template {$template}.");
            return;
        }

        try {
            $client = new PostmarkClient(config('services.postmark.token'));

            $client->sendEmailWithTemplate(
                config('services.postmark.signature'),
                $this->to,
                $template_id,
                $this->template_model
            );
        } catch (PostmarkException $exception) {
            if (406 == $exception->postmarkApiErrorCode) {
                $message = "Mailbox {$this->to} is marked as *Inactive* on Postmark so email {$template} will not be sent.";

                \Log::warning($message);
                ops_warning('Warning', $message, 'engineering');
            } else {
                $contextual_data = ['message' => $exception->message, 'api_error_code' => $exception->postmarkApiErrorCode];
                \Log::error("Unable to send email to {$this->to}.", $contextual_data);

                $message = "Unable to send email to {$this->to}. Message: {$exception->message}, Error code: {$exception->postmarkApiErrorCode}.";
                ops_error('Error', $message, 'engineering');
            }
        }
    }
}
