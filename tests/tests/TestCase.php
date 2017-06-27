<?php

namespace Tests;

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

}
