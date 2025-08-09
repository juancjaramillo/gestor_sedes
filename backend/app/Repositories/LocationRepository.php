<?php

namespace App\Repositories;

use App\Models\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LocationRepository
{
    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return Location::query()
            ->when(!empty($filters['name']), fn($q) => $q->where('name','like','%'.$filters['name'].'%'))
            ->when(!empty($filters['code']), fn($q) => $q->where('code','like','%'.$filters['code'].'%'))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function create(array $data): Location
    {
        return Location::create($data);
    }

    public function update(Location $location, array $data): Location
    {
        $location->fill($data)->save();
        return $location;
    }
}
