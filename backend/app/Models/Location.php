<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $image
 * @use HasFactory<\Database\Factories\LocationFactory>
 */
class Location extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'image'];

    /** @var array<string, string> */
    protected $casts = [
        'id' => 'integer',
        'image' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
