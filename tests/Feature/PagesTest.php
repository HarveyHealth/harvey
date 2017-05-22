<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PagesTest extends TestCase
{
    public function test_if_home_returns_ok()
    {
        $response = $this->get('/');
        $response->isOk();
    }

    public function test_if_lab_tests_returns_ok()
    {
        $response = $this->get('/lab-tests');
        $response->isOk();
    }
}
