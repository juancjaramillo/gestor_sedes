<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Location;

class LocationValidationTest extends TestCase
{
    use RefreshDatabase;

    private string $key;

    protected function setUp(): void
    {
        parent::setUp();
        $this->key = (string) config('api.key');
    }

    public function test_requires_api_key()
    {
        $this->getJson('/api/v1/locations')->assertStatus(401);
    }

    public function test_code_required()
    {
        $payload = ['name' => 'Ciudad X'];
        $this->withHeaders(['x-api-key' => $this->key])
            ->postJson('/api/v1/locations', $payload)
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'E_INVALID_PARAM');
    }

    public function test_name_required()
    {
        $payload = ['code' => 'CIX'];
        $this->withHeaders(['x-api-key' => $this->key])
            ->postJson('/api/v1/locations', $payload)
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'E_INVALID_PARAM');
    }

    public function test_code_unique()
    {
        Location::create(['code' => 'DUP', 'name' => 'Duplicada']);
        $payload = ['code' => 'DUP', 'name' => 'Otra'];
        $this->withHeaders(['x-api-key' => $this->key])
            ->postJson('/api/v1/locations', $payload)
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'E_INVALID_PARAM');
    }
}
