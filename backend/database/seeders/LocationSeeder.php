<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('locations')->insert([
            ['code'=>'BOG','name'=>'Bogotá','image'=>'https://picsum.photos/seed/bog/640','created_at'=>$now,'updated_at'=>$now],
            ['code'=>'MED','name'=>'Medellín','image'=>'https://picsum.photos/seed/med/640','created_at'=>$now,'updated_at'=>$now],
            ['code'=>'CAL','name'=>'Cali','image'=>'https://picsum.photos/seed/cal/640','created_at'=>$now,'updated_at'=>$now],
            ['code'=>'BAR','name'=>'Barranquilla','image'=>'https://picsum.photos/seed/bar/640','created_at'=>$now,'updated_at'=>$now],
            ['code'=>'CAR','name'=>'Cartagena','image'=>'https://picsum.photos/seed/car/640','created_at'=>$now,'updated_at'=>$now],
            ['code'=>'BGA','name'=>'Bucaramanga','image'=>'https://picsum.photos/seed/bga/640','created_at'=>$now,'updated_at'=>$now],
            ['code'=>'PER','name'=>'Pereira','image'=>'https://picsum.photos/seed/per/640','created_at'=>$now,'updated_at'=>$now],
            ['code'=>'MAN','name'=>'Manizales','image'=>'https://picsum.photos/seed/man/640','created_at'=>$now,'updated_at'=>$now],
            ['code'=>'IBG','name'=>'Ibagué','image'=>'https://picsum.photos/seed/ibg/640','created_at'=>$now,'updated_at'=>$now],
            ['code'=>'SMG','name'=>'Santa Marta','image'=>'https://picsum.photos/seed/smg/640','created_at'=>$now,'updated_at'=>$now],
        ]);
    }
}
