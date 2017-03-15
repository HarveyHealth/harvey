<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Test;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResultsUploadTest extends TestCase
{
    use DatabaseMigrations;
    
    public function setUp()
    {
        $this->markTestSkipped('must be revisited.');
    }
    
    public function test_an_admin_can_upload_test_results()
    {
        // GIVEN
        // A TEST
        $test = factory(Test::class)->create();
        // AND A RESULTS FILE
        $file = new UploadedFile(
            storage_path('testing/testfile.pdf'), // Path to local test file
            'testfile.pdf', // The file name
            'application/pdf', // The file mime type
            null, // The file size
            null, // The error constant of the upload
            true); // Test mode
        
        // MOCK CALLS TO S3
        // Mocks for the API Controller saving to S3
        Storage::shouldReceive('disk')->with('s3')->andReturnSelf();
        Storage::shouldReceive('putFileAs')->once();
        // Mocks for the Test Model Pre-signed URL
        Storage::shouldReceive('disk')->with('s3')->andReturnSelf();
        Storage::shouldReceive('getDriver')->andReturnSelf()->once();
        Storage::shouldReceive('getAdapter')->andReturnSelf()->once();
        Storage::shouldReceive('getClient')->andReturnSelf()->once();
        Storage::shouldReceive('getCommand')->andReturnSelf()->once();
        Storage::shouldReceive('createPresignedRequest')->andReturnSelf()->once();
        Storage::shouldReceive('getUri')->andReturn('https://somevalue.com/foobar')->once();
    
        // WHEN WE POST TO THIS ROUTE
        $response = $this->call(
            "POST", // Method
            "/api/alpha/tests/$test->id/results", // URI
            ['api_token' => $test->patient->api_token], // Parameters
            [], // Cookies
            ["file" => $file] // Files
        );
        
        // THEN WE SHOULD SEE
        $response->assertStatus(200);
        $response->assertJson(
            ['meta' =>
                ['uploaded' => true]
            ]
        );
    }
}
