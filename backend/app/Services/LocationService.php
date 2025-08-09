<?php

namespace App\Services;

use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocationService
{
    public function __construct(private LocationRepository $repo) {}

    public function paginateFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repo->paginateFiltered($filters, $perPage);
    }

    public function create(array $data, ?UploadedFile $image): Location
    {
        if ($image) {
            $path = $image->store('locations', 'public');
            $data['image'] = $path;
        }
        $location = $this->repo->create($data);
        return $this->attachImageUrl($location);
    }

    public function update(Location $location, array $data, ?UploadedFile $image): Location
    {
        if ($image) {
            // Borrar imagen previa (opcional)
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('public');
            if ($location->image && $disk->exists($location->image)) {
                $disk->delete($location->image);
            }
            $path = $image->store('locations', 'public');
            $data['image'] = $path;
        }

        $location = $this->repo->update($location, $data);
        return $this->attachImageUrl($location);
    }

    private function attachImageUrl(Location $location): Location
    {
        $location->image_url = $this->imageUrl($location->image);
        return $location;
    }

    private function imageUrl(?string $path): ?string
    {
        if (!$path) return null;

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        return $disk->url($path);
    }
}
