<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class LocationRepository
{
    /**
     * @param  array{name?:string|null, code?:string|null}  $filters
     * @return LengthAwarePaginator<int, Location>
     */
    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $page = (int) (request()->query('page', 1));
        $key = sprintf(
            'locations:%s:%s:%d:%d',
            (string) ($filters['name'] ?? ''),
            (string) ($filters['code'] ?? ''),
            $page,
            $perPage
        );

        $ttl = (int) (config('api.cache_ttl', 30));
        if ($ttl < 1) {
            $ttl = 30;
        }

        /** @var mixed $result */
        $result = Cache::remember($key, $ttl, function () use ($filters, $perPage) {
            $q = Location::query();

            if (! empty($filters['name'])) {
                $q->where('name', 'like', '%'.$filters['name'].'%');
            }
            if (! empty($filters['code'])) {
                $q->where('code', 'like', '%'.$filters['code'].'%');
            }

            return $q->orderBy('name')->paginate($perPage);
        });

        if (! $result instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            $q = Location::query();

            if (! empty($filters['name'])) {
                $q->where('name', 'like', '%'.$filters['name'].'%');
            }
            if (! empty($filters['code'])) {
                $q->where('code', 'like', '%'.$filters['code'].'%');
            }

            /** @var LengthAwarePaginator<int, Location> $result */
            $result = $q->orderBy('name')->paginate($perPage);
        }

        return $result;
    }

    /** @param array{code:string, name:string, image?:string|null} $data */
    public function create(array $data): Location
    {
        return Location::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'image' => $data['image'] ?? null,
        ]);
    }
}
