<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @phpstan-use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\LocationFactory>
 *
 * @method static static create(array<string, mixed> $attributes = [])
 * @method static \Database\Factories\LocationFactory factory(int $count = null, array<string, mixed> $state = [])
 */
class Location extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'image'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function newFactory(): \Database\Factories\LocationFactory
    {
        return \Database\Factories\LocationFactory::new();
    }
}
