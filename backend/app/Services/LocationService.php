<?php

namespace App\Services;

use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LocationService
{
    public function __construct(private readonly LocationRepository $repo) {}

    /**
     * @param  array{name?:string|null, code?:string|null}  $filters
     * @return LengthAwarePaginator<Location>
     */
    public function list(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->repo->paginateFiltered($filters, $perPage);
    }

    /** @param array{code:string, name:string, image?:string|null} $data */
    public function create(array $data): Location
    {
        return $this->repo->create($data);
    }
}
