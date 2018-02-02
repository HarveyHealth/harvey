<?php

namespace Tests\Feature;

use App\Models\LabTestInformation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\{WithoutMiddleware, DatabaseMigrations, DatabaseTransactions};
use ResponseCode;

class PagesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_if_home_returns_ok()
    {
        $response = $this->get('/');
        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    public function test_if_lab_tests_home_returns_found()
    {
        $response = $this->get('/lab-tests');
        $response->assertStatus(ResponseCode::HTTP_FOUND);
    }

    public function test_if_each_lab_tests_redirects_to_store()
    {
        foreach (LabTestInformation::publicFromCache() as $labTest) {
            $response = $this->get("/lab-tests/{$labTest->sku->slug}");
            $this->assertEquals('https://store.goharvey.com/collections/lab-tests', $response->getTargetUrl());
        }
    }

    public function test_if_a_wrong_lab_test_name_is_redirected_to_store()
    {
        $response = $this->get("/lab-tests/an-invalid-name");

        $this->assertEquals('https://store.goharvey.com/collections/lab-tests', $response->getTargetUrl());
    }
}
