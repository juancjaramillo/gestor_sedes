<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class LocationRepository
{
    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $name = trim((string) ($filters['name'] ?? ''));
        $code = trim((string) ($filters['code'] ?? ''));
        $page = (int) request('page', 1);

        $ttl = (int) config('api.cache_ttl', 30);

        $q = Location::query();
        if ($name !== '') $q->where('name', 'like', "%{$name}%");
        if ($code !== '') $q->where('code', 'like', "%{$code}%");
        $q->orderBy('name');

        if ($ttl <= 0) {
            return $q->paginate($perPage);
        }

        $cacheKey = sprintf('locations:%s:%s:%d:%d', $name, $code, $page, $perPage);

        return Cache::remember($cacheKey, $ttl, function () use ($q, $perPage) {
            return $q->paginate($perPage);
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
