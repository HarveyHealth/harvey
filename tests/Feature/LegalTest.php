<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LegalTest extends TestCase
{
    public function test_if_terms_returns_ok()
    {
        $response = $this->get('/terms');
        $response->isOk();
    }

    public function test_if_privacy_returns_ok()
    {
        $response = $this->get('/privacy');
        $response->isOk();
    }
}
