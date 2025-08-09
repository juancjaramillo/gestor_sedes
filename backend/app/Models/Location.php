<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PhpStan/Larastan:
 * - Tipa el trait genérico HasFactory con la factory concreta.
 * - Declara métodos mágicos para evitar falsos positivos.
 *
 * @phpstan-use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\LocationFactory>
 * @method static \Database\Factories\LocationFactory factory(int $count = null, array $state = [])
 * @method static static create(array $attributes = [])
 */
class Location extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'image'];

    protected static function newFactory(): \Database\Factories\LocationFactory
    {
        return \Database\Factories\LocationFactory::new();
    }
}
