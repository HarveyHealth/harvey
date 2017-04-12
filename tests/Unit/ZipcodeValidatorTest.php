<?php

namespace Tests\Unit;

use App\Lib\ZipCodeValidator;
use Tests\TestCase;

class ZipcodeValidatorTest extends TestCase
{
    public function test_it_returns_true_if_we_can_service_the_zipcode()
    {
        $zip_code_validator = app()->make(ZipCodeValidator::class);
        $zip_code_validator->setZip('91106');
        
        $state = $zip_code_validator->getState();
        
        $this->assertEquals($state, "CA");
        $this->assertTrue($zip_code_validator->isServiceable());
    }
    
    public function test_it_returns_false_if_we_cannot_service_the_zipcode()
    {
        $zip_code_validator = app()->make(ZipCodeValidator::class);
        $zip_code_validator->setZip('');
    
        $this->assertFalse($zip_code_validator->isServiceable());
    }
}
