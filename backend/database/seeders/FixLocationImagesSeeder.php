<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixLocationImagesSeeder extends Seeder
{
    public function run(): void
    {
        $all = DB::table('locations')->select('id', 'image')->get();

        foreach ($all as $row) {
            $img = (string) ($row->image ?? '');
            if ($img === '') {
                continue;
            }

            // Si es absoluta: extrae lo que está después de "/storage/"
            if (\preg_match('#https?://[^/]+/storage/(.+)$#i', $img, $m)) {
                $img = $m[1]; // deja "locations/archivo.jpg"
            }

            // Si empieza por "storage/": quítalo
            if (\str_starts_with($img, 'storage/')) {
                $img = \substr($img, \strlen('storage/'));
            }

            // Si quedó vacío, pon null
            $img = $img !== '' ? $img : null;

            DB::table('locations')->where('id', $row->id)->update(['image' => $img]);
        }
    }
}
