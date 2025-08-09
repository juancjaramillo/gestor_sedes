<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read int $id
 * @property-read string $code
 * @property-read string $name
 * @property-read string|null $image
 * @property-read \Illuminate\Support\Carbon|null $created_at
 */
class LocationResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray($request): array
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        $image = $this->image ?: null;
        $imageUrl = $image ? $disk->url($image) : null;

        return [
            'id'         => $this->id,
            'code'       => $this->code,
            'name'       => $this->name,
            'image'      => $image,
            'image_url'  => $imageUrl,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
