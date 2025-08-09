<?php

namespace Tests\Unit;

use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LocationRepositoryTest extends TestCase
{
    use RefreshDatabase; // <-- asegura migraciones para SQLite en memoria

    public function test_caches_index_results(): void
    {
        // Dato mínimo en DB para coherencia (aunque devolvemos paginator mockeado)
        Location::query()->create(['code' => 'BOG', 'name' => 'Bogotá']);

        // Prepara un paginator consistente como retorno del cache
        $items = new Collection([
            new Location(['id' => 1, 'code' => 'BOG', 'name' => 'Bogotá', 'image' => null]),
        ]);

        $paginator = new LengthAwarePaginator(
            $items,
            $items->count(), // total
            10,              // per page
            1                // current page
        );

        // Mock del Facade Cache: evita encadenar ->once() para no molestar a PHPStan
        Cache::shouldReceive('remember')->andReturn($paginator);

        $repo = new LocationRepository();

        // Primera llamada
        $result1 = $repo->paginateFiltered(['name' => 'BO'], 10);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result1);
        $this->assertSame(1, $result1->total());

        // Segunda llamada (usa cache)
        $result2 = $repo->paginateFiltered(['name' => 'BO'], 10);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result2);
        $this->assertSame(1, $result2->total());
    }
}
