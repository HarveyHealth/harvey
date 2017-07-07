<?php

namespace App\Lib;

use App\Jobs\SendTransactionalEmail;

class TransactionalEmail
{
    protected $sendTransactionalEmaill;

    public function __construct(SendTransactionalEmail $sendTransactionalEmail)
    {
        $this->sendTransactionalEmail = $sendTransactionalEmail;
    }

    public static function createJob(string $to = '', string $template = '', array $templateData = [])
    {
        return \App::make(self::class)->sendTransactionalEmail->setTo($to)->setTemplate($template)->setTemplateModel($templateData);
    }
}
