<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Postmark\PostmarkClient;
use Postmark\Models\PostmarkException;
use Log;

class SendTransactionalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $to;
    protected $template;
    protected $template_id;
    protected $template_model;

    const ALLOWED_EMPTY_KEYS = ['doctor_state'];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to = '', $template = '', Array $template_model = [])
    {
        $this->to = $to;
        $this->template = $template;
        $this->template_model = $template_model;
    }

    public function setTo($value = '')
    {
        $this->to = $value;
        return $this;
    }

    public function setTemplate($value = '')
    {
        $this->template = $value;
        return $this;
    }

    public function setTemplateModel(Array $value = [])
    {
        $this->template_model = $value;
        return $this;
    }

    protected function checkForEmptyKeys()
    {
        foreach ($this->template_model as $key => $value) {
            if (empty($value) && !in_array($value, self::ALLOWED_EMPTY_KEYS)) {
                $message = "Found empty value for key '{$key}' when sending email to '{$this->to}' with template '{$this->template}'.";
                Log::warning($message);
            }
        }

        if (empty($this->to)) {
            Log::error("Found empty 'to' value when sending email with template '{$this->template}'.");
        }

        if (!(is_numeric($this->template_id))) {
            Log::error("Wrong 'template' value when sending email to '{$this->to}'.");
        }
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->template = str_replace('services.postmark.templates.', '', $this->template);

        $this->template_id = config("services.postmark.templates.{$this->template}");

        $this->checkForEmptyKeys();

        if (!app()->environment(['production','staging'])) {
            Log::info("Faking sending transactional email with Postmark to '{$this->to}' with template '{$this->template}'.");
            return;
        }

        try {
            $client = new PostmarkClient(config('services.postmark.token'));
            $client->sendEmailWithTemplate(config('services.postmark.signature'), $this->to, $this->template_id, $this->template_model);
        } catch (PostmarkException $exception) {
            if (406 == $exception->postmarkApiErrorCode) {
                $message = "Mailbox '{$this->to}' is marked as *Inactive* on Postmark so email '{$this->template}' will not be sent.";

                Log::warning($message);
                ops_warning('Warning', $message, 'engineering');
            } else {
                $contextual_data = ['message' => $exception->message, 'api_error_code' => $exception->postmarkApiErrorCode];
                Log::error("Unable to send email to '{$this->to}'.", $contextual_data);

                $message = "Unable to send email to '{$this->to}'. Message: '{$exception->message}', Error code: '{$exception->postmarkApiErrorCode}'.";
                ops_error('Error', $message, 'engineering');
            }
        }
    }
}
