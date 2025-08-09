<?php

namespace App\Services;

use App\Repositories\LocationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Location;

class LocationService
{
    public function __construct(private readonly LocationRepository $repo) {}

    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->repo->paginateFiltered($filters, $perPage);
    }

    public function create(array $data): Location
    {
        return $this->repo->create($data);
    }
}
