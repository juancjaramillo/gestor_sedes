<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Location;

/** @mixin Location */
class LocationResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Location $loc */
        $loc = $this->resource;

        return [
            'id'         => $loc->id,
            'code'       => $loc->code,
            'name'       => $loc->name,
            'image_url'  => $loc->image ? asset("storage/{$loc->image}") : null,
            'created_at' => $loc->created_at?->toISOString(),
            'updated_at' => $loc->updated_at?->toISOString(),
        ];
    }
}
