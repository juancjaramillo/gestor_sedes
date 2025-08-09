<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\LocationIndexRequest;
use App\Http\Requests\Location\LocationStoreRequest;
use App\Http\Requests\Location\LocationUpdateRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function __construct(private LocationService $service) {}

    public function index(LocationIndexRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 10);

        $paginator = $this->service->paginateFiltered($filters, $perPage);

        return response()->json([
            'data' => LocationResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    public function store(LocationStoreRequest $request): JsonResponse
    {
        $location = $this->service->create($request->validated(), $request->file('image'));

        return response()->json([
            'data' => new LocationResource($location),
        ], 201);
    }

    public function update(LocationUpdateRequest $request, Location $location): JsonResponse
    {
        $location = $this->service->update($location, $request->validated(), $request->file('image'));

        return response()->json([
            'data' => new LocationResource($location),
        ]);
    }
}
