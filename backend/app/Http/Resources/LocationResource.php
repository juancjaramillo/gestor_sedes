<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LocationResource extends JsonResource
{
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
            'created_at' => optional($this->created_at)?->toISOString(),
        ];
    }
}
