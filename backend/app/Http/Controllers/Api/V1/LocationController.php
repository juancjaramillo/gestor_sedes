<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationIndexRequest;
use App\Http\Requests\LocationStoreRequest;
use App\Http\Resources\LocationResource;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function __construct(private readonly LocationService $service) {}

    public function index(LocationIndexRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $per = (int) ($validated['per_page'] ?? 10);
        $items = $this->service->list($validated, $per);

        return response()->json([
            'data' => LocationResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'last_page' => $items->lastPage(),
            ],
        ]);
    }

    public function store(LocationStoreRequest $request): JsonResponse
    {
        /** @var array{code:string, name:string, image?:string|null} $data */
        $data = $request->validated();

        $location = $this->service->create($data);

        return response()->json([
            'data' => new LocationResource($location),
        ], 201);
    }
}
