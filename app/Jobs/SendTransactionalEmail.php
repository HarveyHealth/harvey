<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Postmark\PostmarkClient;
use Postmark\Models\PostmarkException;
use App\Models\User;
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
    public function __construct(string $to = '', string $template = '', Array $template_model = [])
    {
        $this->to = $to;
        $this->template = $template;
        $this->template_model = $template_model;
    }

    public function setTo(string $value = '')
    {
        $this->to = $value;
        return $this;
    }

    public function setTemplate(string $value = '')
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
            if (empty($value) && !in_array($key, self::ALLOWED_EMPTY_KEYS)) {
                $message = "Found empty value for key '{$key}' when sending email to '{$this->to}' with template '{$this->template}'.";

                if ($user = User::where('email', $this->to)->first()) {
                    $contextualData = [
                        'user' => [
                            'city' => $user->city,
                            'email_verified_at' => empty($user->email_verified_at) ? null : $user->email_verified_at->toW3cString(),
                            'full_name' => $user->full_name,
                            'state' => $user->state,
                            'timezone' => $user->timezone,
                            'zip' => $user->zip,
                        ],
                    ];
                }

                Log::warning($message, $contextualData ?? []);
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

        if (isNotStgOrProd()) {
            return Log::info("Faking sending transactional email with Postmark to '{$this->to}' with template '{$this->template}'.");
        }

        try {
            $client = new PostmarkClient(config('services.postmark.token'));
            $client->sendEmailWithTemplate(config('services.postmark.signature'), $this->to, $this->template_id, $this->template_model);
        } catch (PostmarkException $exception) {
            if (406 == $exception->postmarkApiErrorCode) {
                $message = "Mailbox '{$this->to}' is marked as *Inactive* on Postmark so email '{$this->template}' will not be sent.";
                ops_warning('Warning', $message, 'engineering');
            } else {
                $contextualData = ['message' => $exception->message, 'api_error_code' => $exception->postmarkApiErrorCode];
                Log::error("Unable to send email to '{$this->to}'.", $contextualData);
            }
        }
    }
}
