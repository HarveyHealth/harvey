<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertEmailWasSentTo($to = '')
    {
        $this->assertNotEmpty($this->email_mock->shouldHaveReceived('setTo')->with($to));
    }

    protected function assertEmailTemplateNameWas($template_name = '')
    {
        $this->assertNotEmpty($this->email_mock->shouldHaveReceived('setTemplate')->with($template_name));
    }

    protected function assertEmailTemplateDataWas(array $template_data = [])
    {
        $this->assertNotEmpty($this->email_mock->shouldHaveReceived('setTemplateModel')->with($template_data));
    }

    protected function assertTextWasSent(int $number, string $message)
    {
        $this->assertNotEmpty($this->sms_mock->shouldHaveReceived('sendMessageToNumber')->withArgs([$number, $message]));
    }

    protected function getCommandOutput(string $command)
    {
        $kernel = $this->app->make(Kernel::class);
        $input = new ArrayInput(['command' => $command]);
        $output = new BufferedOutput;

        $status = $kernel->handle($input, $output);

        return explode("\n", $output->fetch());
    }

    protected function getRandomValidPhone()
    {
        return array_random(['626','323','818']) . rand(1111111, 9999999);
    }
}
