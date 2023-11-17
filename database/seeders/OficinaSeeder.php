<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Oficina;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OficinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Oficina::create([
            'name' => 'Dirección General',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Oficina::create([
            'name' => 'Dirección del Registro Público de la Propiedad',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Oficina::create([
            'name' => 'Dirección de Catastro',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Oficina::create([
            'name' => 'Subdirección de planeación estratégica',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Oficina::create([
            'name' => 'Subdirección Jurídica',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Oficina::create([
            'name' => 'Subdirección de Tecnologías de la Información',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);


        Oficina::create([
            'name' => 'Delegación Administrativa',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

    }
}
