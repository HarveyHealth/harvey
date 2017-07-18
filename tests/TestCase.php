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
        $this->assertNotEmpty($this->emailMock->shouldHaveReceived('setTo')->with($to));
    }

    protected function assertEmailTemplateNameWas($templateName = '')
    {
        $this->assertNotEmpty($this->emailMock->shouldHaveReceived('setTemplate')->with($templateName));
    }

    protected function assertEmailTemplateDataWas(Array $templateData = [])
    {
        $this->assertNotEmpty($this->emailMock->shouldHaveReceived('setTemplateModel')->with($templateData));
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
