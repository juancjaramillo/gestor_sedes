<?php

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Asegura que la API key esté seteada
        config(['api.key' => 'super-secret-key-123']);
    }

    public function test_rejects_without_api_key(): void
    {
        $this->getJson('/api/v1/locations')->assertStatus(401);
    }

    public function test_lists_locations_with_pagination_and_filters(): void
    {
        Location::factory()->create(['code' => 'BOG', 'name' => 'Bogotá']);
        Location::factory()->create(['code' => 'MED', 'name' => 'Medellín']);

        $res = $this->withHeaders(['x-api-key' => 'super-secret-key-123'])
            ->getJson('/api/v1/locations?name=BOG&per_page=1');

        $res->assertOk()
            ->assertJsonStructure([
                'data',
                'meta' => ['current_page','per_page','total','last_page'],
            ]);
    }

    public function test_creates_a_location(): void
    {
        $res = $this->withHeaders(['x-api-key' => 'super-secret-key-123'])
            ->postJson('/api/v1/locations', ['code' => 'TUN', 'name' => 'Tunja']);

        $res->assertCreated()->assertJsonPath('code', 'TUN');
    }
}
