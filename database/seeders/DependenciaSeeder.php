<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Dependencia;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DependenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Dependencia::create([
            'name' => 'Secreatria de Gobierno',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependencia::create([
            'name' => 'Secreatria de Bienestar',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependencia::create([
            'name' => 'Secreatria de Finanzas',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependencia::create([
            'name' => 'Secreatria de Migración',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependencia::create([
            'name' => 'Secreatria de Hacienda',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependencia::create([
            'name' => 'Secreatria de la Mujer',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependencia::create([
            'name' => 'Secreatria del trabajo',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

    }
}
