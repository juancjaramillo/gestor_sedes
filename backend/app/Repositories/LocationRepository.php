<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class LocationRepository
{
    /**
     * @param array{name?: string|null, code?: string|null} $filters
     * @return LengthAwarePaginator<int, Location>
     */
    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $page = (int) request()->integer('page', 1);
        $ttl  = (int) config('api.cache_ttl', 60);

        $key = 'locations:index:' . md5(json_encode([$filters, $perPage, $page], JSON_THROW_ON_ERROR));

         $build = function () use ($filters, $perPage): LengthAwarePaginator {
            $q = Location::query();

            if (!empty($filters['name'])) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            }
            if (!empty($filters['code'])) {
                $q->where('code', 'like', '%' . $filters['code'] . '%');
            }

            /** @var LengthAwarePaginator<int, Location> $p */
            $p = $q->orderByDesc('id')->paginate($perPage);

            return $p;
        };

        /** @var LengthAwarePaginator<int, Location>|null $result */
        $result = Cache::remember($key, now()->addSeconds($ttl), $build);

        
        return $result ?? $build();
    }

    /**
     * @param array{code: string, name: string, image?: string|null} $data
     */
    public function create(array $data): Location
    {
        return Location::create($data);
    }
}
