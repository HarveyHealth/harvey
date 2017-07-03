<?php

namespace Tests\Feature;

use App\Models\Practitioner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use ResponseCode;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserImageUploadTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_accepts_an_image_and_uploads_it_to_s3()
    {
//        Storage::fake('avatars');
        
        $practitioner = factory(Practitioner::class)->create();
        Passport::actingAs($practitioner->user);
        
        
        
        $response = $this->json('POST', "api/v1/users/{$practitioner->user->id}/image", ['image' => UploadedFile::fake()->image('avatar.jpg')]);
        Storage::disk('s3')->assertExists('avatar.jpg');
        
    }
}
