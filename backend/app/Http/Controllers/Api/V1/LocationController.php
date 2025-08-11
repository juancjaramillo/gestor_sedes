<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    public function __construct(
        private readonly LocationService $service,
    ) {}

    private function guardApiKey(Request $request): ?JsonResponse
    {
        $provided = (string) $request->header('x-api-key', '');
        $expected = (string) config('api.key', '');

        if ($expected === '' || ! hash_equals($expected, $provided)) {
            return response()->json([
                'error' => [
                    'code' => 'E_UNAUTHORIZED',
                    'message' => 'Missing or invalid API key',
                ],
            ], 401);
        }

        return null;
    }

    public function index(Request $request): JsonResponse
    {
        if ($resp = $this->guardApiKey($request)) {
            return $resp;
        }

        $filters = [
            'name' => $request->string('name')->toString(),
            'code' => $request->string('code')->toString(),
        ];
        $perPage = (int) $request->integer('per_page', 10);

        $p = $this->service->list($filters, $perPage);

        return response()->json([
            'data' => LocationResource::collection($p->items()),
            'meta' => [
                'current_page' => $p->currentPage(),
                'last_page' => $p->lastPage(),
                'per_page' => $p->perPage(),
                'total' => $p->total(),
            ],
        ]);
    }

    public function show(Request $request, Location $location): JsonResponse|LocationResource
    {
        if ($resp = $this->guardApiKey($request)) {
            return $resp;
        }

        return new LocationResource($location);
    }

    public function store(LocationStoreRequest $request): LocationResource|JsonResponse
    {
        if ($resp = $this->guardApiKey($request)) {
            return $resp;
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('locations', 'public');
            $data['image'] = asset('storage/'.$path);
        } else {
            $data['image'] = $data['image'] ?? null;
        }

        $loc = $this->service->create($data);

        return new LocationResource($loc);
    }

    public function update(LocationUpdateRequest $request, Location $location): LocationResource|JsonResponse
    {
        if ($resp = $this->guardApiKey($request)) {
            return $resp;
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($location->image) {
                $prefixes = [
                    '/storage/',
                    rtrim(url('/storage'), '/').'/',
                ];

                foreach ($prefixes as $prefix) {
                    if (str_starts_with($location->image, $prefix)) {
                        $relative = ltrim(str_replace($prefix, '', $location->image), '/');
                        Storage::disk('public')->delete($relative);
                        break;
                    }
                }
            }

            $path = $request->file('image')->store('locations', 'public');
            $data['image'] = asset('storage/'.$path);
        } else {
            $data['image'] = $location->image;
        }

        $location = $this->service->update($location, $data);

        return new LocationResource($location);
    }

    public function destroy(Request $request, Location $location): JsonResponse
    {
        if ($resp = $this->guardApiKey($request)) {
            return $resp;
        }

        if ($location->image) {
            $prefixes = [
                '/storage/',
                rtrim(url('/storage'), '/').'/',
            ];

            foreach ($prefixes as $prefix) {
                if (str_starts_with($location->image, $prefix)) {
                    $relative = ltrim(str_replace($prefix, '', $location->image), '/');
                    Storage::disk('public')->delete($relative);
                    break;
                }
            }
        }

        $location->delete();

        return response()->json([], 204);
    }
}
