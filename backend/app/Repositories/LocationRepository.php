<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class LocationRepository
{
    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $key = sprintf(
            'locations:%s:%s:%d:%d',
            $filters['name'] ?? '',
            $filters['code'] ?? '',
            (int) request()->query('page', 1),
            $perPage
        );

        $ttl = (int) config('api.cache_ttl', 30);

        return Cache::remember($key, $ttl, function () use ($filters, $perPage) {
            $q = Location::query();

            if (!empty($filters['name'])) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            }
            if (!empty($filters['code'])) {
                $q->where('code', 'like', '%' . $filters['code'] . '%');
            }

            return $q->orderBy('name')->paginate($perPage)->withQueryString();
        });
    }

    public function create(array $data): Location
    {
        return Location::create([
            'code'  => $data['code'],
            'name'  => $data['name'],
            'image' => $data['image'] ?? null,
        ]);
    }
}
