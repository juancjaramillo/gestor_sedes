<?php

namespace App\Services;

use App\Models\Location;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LocationService
{
    /**
     * Método para obtener una lista paginada de ubicaciones.
     *
     * @param array $filters Filtros para la búsqueda
     * @param int $perPage Número de resultados por página
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function list(array $filters, int $perPage = 10)
    {
        $query = Location::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['code'])) {
            $query->where('code', 'like', '%' . $filters['code'] . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Método para crear una nueva ubicación.
     *
     * @param array $data Datos validados para crear una nueva ubicación
     * @return Location
     */
    public function create(array $data): Location
    {
        return Location::create($data);
    }

    /**
     * Método para actualizar una ubicación existente.
     *
     * @param Location $location Ubicación a actualizar
     * @param array $data Datos validados para la actualización
     * @return Location
     */
    public function update(Location $location, array $data): Location
    {
        $location->update($data);
        return $location;
    }
}
