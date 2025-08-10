<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'BOG', 'name' => 'Bogotá',       'seed' => 'bogota'],
            ['code' => 'MED', 'name' => 'Medellín',     'seed' => 'medellin'],
            ['code' => 'CAL', 'name' => 'Cali',         'seed' => 'cali'],
            ['code' => 'BAR', 'name' => 'Barranquilla', 'seed' => 'barranquilla'],
            ['code' => 'CAR', 'name' => 'Cartagena',    'seed' => 'cartagena'],
            ['code' => 'BGA', 'name' => 'Bucaramanga',  'seed' => 'bucaramanga'],
            ['code' => 'PER', 'name' => 'Pereira',      'seed' => 'pereira'],
            ['code' => 'MAN', 'name' => 'Manizales',    'seed' => 'manizales'],
            ['code' => 'IBG', 'name' => 'Ibagué',       'seed' => 'ibague'],
            ['code' => 'SMG', 'name' => 'Santa Marta',  'seed' => 'santamarta'],
        ];

        foreach ($rows as $row) {
            $url = "https://picsum.photos/seed/{$row['seed']}/640/360";
            $resp = Http::timeout(10)->get($url);

            $filename = null;
            if ($resp->ok()) {
                $filename = 'locations/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($filename, $resp->body());
            }

            Location::updateOrCreate(
                ['code' => $row['code']],
                ['name' => $row['name'], 'image' => $filename]
            );
        }
    }
}
