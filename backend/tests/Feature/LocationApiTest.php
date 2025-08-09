<?php

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationApiTest extends TestCase
{
    use RefreshDatabase;

    private string $key;

    protected function setUp(): void
    {
        parent::setUp();
        // Lee la API key desde config => .env.testing
        $this->key = (string) config('api.key');
    }

    public function test_rejects_without_api_key(): void
    {
        $this->getJson('/api/v1/locations')->assertStatus(401);
    }

    public function test_lists_locations_with_pagination_and_filters(): void
    {
        Location::factory()->create(['code' => 'BOG', 'name' => 'Bogotá']);
        Location::factory()->create(['code' => 'MED', 'name' => 'Medellín']);
        Location::factory()->create(['code' => 'BAR', 'name' => 'Barranquilla']);
        Location::factory()->count(5)->create();

        $headers = ['x-api-key' => $this->key];

        $res = $this->withHeaders($headers)
            ->getJson('/api/v1/locations?name=bo&per_page=5&page=1');

        $res->assertOk();

        $res->assertJsonStructure([
            'data' => [
                '*' => ['id', 'code', 'name', 'image', 'created_at'],
            ],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);

        $codes = collect($res->json('data'))->pluck('code');
        $this->assertTrue($codes->contains('BOG'));
    }

    public function test_creates_a_location(): void
    {
        $headers = ['x-api-key' => $this->key];

        $payload = [
            'code' => 'ABC',
            'name' => 'Ciudad ABC',
        ];

        $res = $this->withHeaders($headers)->postJson('/api/v1/locations', $payload);

        // 200 o 201, según tu controlador
        $this->assertTrue(in_array($res->getStatusCode(), [200, 201], true), 'Expected 200 or 201');

        $json = $res->json();

        // Soportar ambas estructuras: {data:{...}} ó {...}
        $code = data_get($json, 'data.code', data_get($json, 'code'));
        $name = data_get($json, 'data.name', data_get($json, 'name'));

        $this->assertSame('ABC', $code);
        $this->assertSame('Ciudad ABC', $name);

        $this->assertDatabaseHas('locations', [
            'code' => 'ABC',
            'name' => 'Ciudad ABC',
        ]);
    }
}
