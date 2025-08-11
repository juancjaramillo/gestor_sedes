<?php

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LocationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function withKey(array $headers = []): array
    {
        return array_merge(['x-api-key' => config('api.key')], $headers);
    }

    public function test_index_returns_paginated_list(): void
    {
        Location::factory()->count(3)->create();

        $res = $this->withHeaders($this->withKey())
            ->getJson('/api/v1/locations?per_page=2');

        $res->assertOk()
            ->assertJsonStructure(['data', 'meta' => ['current_page', 'last_page', 'per_page', 'total']]);
    }

    public function test_store_creates_location(): void
    {
        $payload = ['code' => 'BOG', 'name' => 'Bogotá'];

        $res = $this->withHeaders($this->withKey())
            ->postJson('/api/v1/locations', $payload);

        $res->assertCreated()
            ->assertJsonPath('data.code', 'BOG')
            ->assertJsonPath('data.name', 'Bogotá');

        $this->assertDatabaseHas('locations', $payload);
    }

    public function test_update_keeps_same_code_without_unique_violation(): void
    {
        $loc = Location::factory()->create(['code' => 'BOG', 'name' => 'Bogotá']);

        $res = $this->withHeaders($this->withKey())
            ->putJson("/api/v1/locations/{$loc->id}", [
                'code' => 'BOG',
                'name' => 'Bogotá D.C.',
            ]);

        $res->assertOk()
            ->assertJsonPath('data.code', 'BOG')
            ->assertJsonPath('data.name', 'Bogotá D.C.');

        $this->assertDatabaseHas('locations', ['id' => $loc->id, 'name' => 'Bogotá D.C.']);
    }

    public function test_update_fails_when_code_duplicates_other_row(): void
    {
        $a = Location::factory()->create(['code' => 'BOG']);
        $b = Location::factory()->create(['code' => 'MED']);

        $res = $this->withHeaders($this->withKey())
            ->putJson("/api/v1/locations/{$a->id}", [
                'code' => 'MED',
                'name' => 'Bogotá',
            ]);

        $res->assertStatus(422);
    }

    public function test_destroy_deletes_location(): void
    {
        $loc = Location::factory()->create();

        $res = $this->withHeaders($this->withKey())
            ->deleteJson("/api/v1/locations/{$loc->id}");

        $res->assertNoContent();
        $this->assertDatabaseMissing('locations', ['id' => $loc->id]);
    }
}
