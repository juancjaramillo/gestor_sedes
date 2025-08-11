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
    
        Location::create(['code' => 'BOG', 'name' => 'Bogotá']);

    
        Cache::spy();

        
        $this->app['request']->merge(['page' => 1]);

       
        $repo = app(LocationRepository::class);
        $repo->paginateFiltered(['name' => 'BO'], 10);

    
        Cache::shouldHaveReceived('remember')->atLeast()->once();
    }
}
