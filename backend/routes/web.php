<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/storage/{path}', function (string $path) {

    $path = ltrim($path, '/');
    if (str_contains($path, '..')) {
        abort(403);
    }

    $full = storage_path('app/public/'.$path);

    if (! File::exists($full)) {
        abort(404);
    }

    $mime = File::mimeType($full) ?: 'application/octet-stream';

    return Response::make(File::get($full), 200, ['Content-Type' => $mime]);
})->where('path', '.*');
