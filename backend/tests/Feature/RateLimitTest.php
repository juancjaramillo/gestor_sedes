<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateLimitTest extends TestCase
{
    use RefreshDatabase;

    private string $key;

    protected function setUp(): void
    {
        parent::setUp();
        $this->key = (string) config('api.key'); // viene de .env.testing
        config(['api.rate_limit' => 2]);         // bajamos el límite solo para este test
    }

    public function test_throttles_by_api_key(): void
    {
        $headers = ['x-api-key' => $this->key];

        $this->withHeaders($headers)->getJson('/api/v1/locations')->assertOk();
        $this->withHeaders($headers)->getJson('/api/v1/locations')->assertOk();
        $this->withHeaders($headers)->getJson('/api/v1/locations')->assertStatus(429);
    }
}
