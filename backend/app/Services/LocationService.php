<?php

namespace App\Services;

use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocationService
{
    public function __construct(private LocationRepository $repo)
    {
    }

    /**
     * @param  array<string, mixed>  $filters
     * @phpstan-return LengthAwarePaginator<int, Location>
     */
    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repo->paginateFiltered($filters, $perPage);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data, ?UploadedFile $image): Location
    {
        if ($image) {
            $path = $image->store('locations', 'public');
            $data['image'] = $path;
        }
        return $this->repo->create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Location $location, array $data, ?UploadedFile $image): Location
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if ($image) {
            if ($location->image && $disk->exists($location->image)) {
                $disk->delete($location->image);
            }
            $data['image'] = $image->store('locations', 'public');
        }
        return $this->repo->update($location, $data);
    }
}
