<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\LocationFactory>
 */
class Location extends Model
{
    use HasFactory;

    // Si tu tabla se llama "locations", puedes omitir esto.
    // protected $table = 'locations';

    /**
     * Campos permitidos para asignación masiva.
     */
    protected $fillable = [
        'code',
        'name',
        'image',
    ];

    /**
     * Casts opcionales (no estrictamente necesarios).
     */
    protected $casts = [
        'code'  => 'string',
        'name'  => 'string',
        'image' => 'string',
    ];
}
