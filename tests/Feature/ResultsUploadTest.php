<?php

namespace Tests\Feature;

use App\Models\Test;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;

class ResultsUploadTest extends TestCase
{
    use DatabaseMigrations;
    
    public function test_the_api_will_store_a_new_test_result()
    {
        // GIVEN
        $test = factory(Test::class)->create();
        $file = new UploadedFile(storage_path('testing/testfile.pdf'), 'testfile.pdf');
        
        // EXPECT
        Storage::shouldReceive('disk')->with('s3')->andReturnSelf();
        Storage::shouldReceive('putFileAs')->once();
        Storage::shouldReceive('url')->once();
    
        // WHEN
        $response = $this->call(
            "POST",
            "/api/alpha/tests/$test->id/results",
            ['api_token' => $test->patient->api_token],
            [],
            ["file" => $file]);

        // THEN
        $response->assertStatus(200);
        $response->assertJson(
            ['meta' =>
                ['uploaded' => true]
            ]
        );
    }
}
