<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
      
        if (! File::exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        $rows = [
            ['code' => 'BOG', 'name' => 'Bogotá',       'file' => 'bogota.jpg'],
            ['code' => 'MED', 'name' => 'Medellín',     'file' => 'medellin.jpg'],
            ['code' => 'CAL', 'name' => 'Cali',         'file' => 'cali.jpg'],
            ['code' => 'BAR', 'name' => 'Barranquilla', 'file' => 'barranquilla.jpg'],
            ['code' => 'CAR', 'name' => 'Cartagena',    'file' => 'cartagena.jpg'],
            ['code' => 'BGA', 'name' => 'Bucaramanga',  'file' => 'bucaramanga.jpg'],
            ['code' => 'PER', 'name' => 'Pereira',      'file' => 'pereira.jpg'],
            ['code' => 'MAN', 'name' => 'Manizales',    'file' => 'manizales.jpg'],
            ['code' => 'IBG', 'name' => 'Ibagué',       'file' => 'ibague.jpg'],
            ['code' => 'SMG', 'name' => 'Santa Marta',  'file' => 'santamarta.jpg'],
        ];

        $srcDir = resource_path('seeders/locations');

        foreach ($rows as $row) {
            $imageUrl = null;

            $src = $srcDir . DIRECTORY_SEPARATOR . $row['file'];
            if (File::exists($src)) {
                $target = 'locations/' . $row['file'];
                Storage::disk('public')->put($target, File::get($src));

            
                $imageUrl = Storage::url($target);
            }

            Location::updateOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name'], 'image' => $imageUrl]
            );
        }
    }
}
