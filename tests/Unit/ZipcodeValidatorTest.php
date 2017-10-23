<?php

namespace Tests\Unit;

use App\Lib\ZipCodeValidator;
use App\Models\License;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ZipcodeValidatorTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_returns_true_if_we_have_a_license_for_a_regulated_zipcode()
    {
        factory(License::class)->create(['state' => 'CA']);

        $zip_code_validator = app()->make(ZipCodeValidator::class);
        $zip_code_validator->setZip('91106');

        $state = $zip_code_validator->getState();

        $this->assertEquals('CA', $state);
        $this->assertTrue($zip_code_validator->isServiceable());
    }

    public function test_it_returns_false_if_we_dont_have_a_license_for_a_regulated_zipcode()
    {
        $zip_code_validator = app()->make(ZipCodeValidator::class);
        $zip_code_validator->setZip('91106');

        $state = $zip_code_validator->getState();

        $this->assertEquals('CA', $state);
        $this->assertFalse($zip_code_validator->isServiceable());
    }

    public function test_it_returns_false_if_we_cannot_service_the_zipcode()
    {
        $zip_code_validator = app()->make(ZipCodeValidator::class);
        $zip_code_validator->setZip('');

        $this->assertFalse($zip_code_validator->isServiceable());
    }
}
