<?php

namespace App\Services;

use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class LocationService
{
    public function __construct(private LocationRepository $repo) {}

    /** @param array{name?:string|null, code?:string|null} $filters */
    public function list(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repo->paginateFiltered($filters, $perPage);
    }

    /** @param array{code:string, name:string, image?:string|null} $data */
    public function create(array $data): Location
    {
        $loc = $this->repo->create($data);
        Cache::flush();

        return $loc;
    }

    /** @param array{code:string, name:string, image?:string|null} $data */
    public function update(Location $loc, array $data): Location
    {
        $loc->fill([
            'code'  => $data['code'],
            'name'  => $data['name'],
            'image' => $data['image'] ?? null,
        ])->save();

        Cache::flush();

        return $loc;
    }
}
