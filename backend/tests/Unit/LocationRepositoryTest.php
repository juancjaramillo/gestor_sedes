<?php

namespace Tests\Unit;

use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LocationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_caches_index_results(): void
    {
        // Arrange
        Location::create(['code' => 'BOG', 'name' => 'Bogotá']);

        // Espiar Cache facade
        Cache::spy();

        // Simula query param page para la clave de cache
        $this->app['request']->merge(['page' => 1]);

        // Act
        $repo = app(LocationRepository::class);
        $repo->paginateFiltered(['name' => 'BO'], 10);

        // Assert: 'remember' fue llamado al menos una vez
        // @phpstan-ignore-next-line -- Método de Mockery en Facade, válido en test
        Cache::shouldHaveReceived('remember')->atLeast()->once();
        // Si prefieres exacto:
        // @phpstan-ignore-next-line -- Método de Mockery en Facade, válido en test
        // Cache::shouldHaveReceived('remember')->once();
    }
}
