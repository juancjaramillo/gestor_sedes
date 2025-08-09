<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @phpstan-use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\LocationFactory>
 *
 * @method static \Database\Factories\LocationFactory factory($count = null, $state = [])
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
