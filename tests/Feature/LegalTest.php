<?php

namespace Tests\Feature;

use Tests\TestCase;
use ResponseCode;

class LegalTest extends TestCase
{
    public function test_if_terms_returns_ok()
    {
        $response = $this->get('/terms');
        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    public function test_if_privacy_returns_ok()
    {
        $response = $this->get('/privacy');
        $response->assertStatus(ResponseCode::HTTP_OK);
    }
}
