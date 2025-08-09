<?php

namespace App\Models;

use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Ajustes para PHPStan/Larastan:
 * - Anotamos el trait genérico HasFactory con la factory concreta usando @use.
 * - Tipamos los arrays de create() y factory() para evitar missingType.iterableValue.
 *
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\LocationFactory>
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static \Database\Factories\LocationFactory factory(int $count = null, array<string, mixed> $state = [])
 */
class Location extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'image'];

    protected static function newFactory(): LocationFactory
    {
        return LocationFactory::new();
    }
}
