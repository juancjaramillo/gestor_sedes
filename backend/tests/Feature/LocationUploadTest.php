<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LocationUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');         // fake de disco
        Config::set('api.key', 'sk_test_123'); // API key
    }

    public function test_store_location_with_image_upload(): void
    {
        $file = UploadedFile::fake()->image('sede.png', 200, 200);

        $resp = $this->postJson('/api/v1/locations', [
            'code'  => 'BOG',
            'name'  => 'Bogotá',
            'image' => $file,
        ], ['x-api-key' => 'sk_test_123']);

        $resp->assertCreated()
             ->assertJsonStructure(['data' => ['id','code','name','image','image_url']]);

        $path = (string) $resp->json('data.image');

        // Como usamos Storage::fake, 'exists' funciona y no rompe PHPStan
        $this->assertTrue(Storage::disk('public')->exists($path));
    }
}
