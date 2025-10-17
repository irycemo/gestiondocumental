<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Enrique',
            'email' => 'enrique_j_@hotmail.com',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 6,
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Jesus Manriquez Vargas',
            'email' => 'subdirti.irycem@correo.michoacan.gob.mx',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 6,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Paulina Lucero González Núñez',
            'email' => 'subdirjuridico.irycem@correo.michoacan.gob.mx',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 5,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Heladio Vargas Gutierrez',
            'email' => 'heladiovarg@gmail.com',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 6,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Paricutin Rosas Mendoza',
            'email' => 'paricutin.irycem@gmail.com',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 5,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Fernando Antonio Solorio Romero',
            'email' => 'lic.solsol79@gmail.com',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 5,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Eva Janeth Rodriguez Garcia',
            'email' => 'evarodriguez98.zz@gmail.com',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 5,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Fabián Gonzalez Gomez',
            'email' => 'fabian.gonzalez.fg@hotmail.com',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 5,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Estefania Sanchez Soto',
            'email' => 'fanny.snst95@gmail.com',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 6,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Antonio Guía García',
            'email' => 'tony_guia@outlook.com',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 5,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Miguel Angel Salazar Alvarez',
            'email' => 'miguel.salazar.fdcs@hotmail.com',
            'email_verified_at' => Carbon::now(),
            'status' => 'activo',
            'oficina_id' => 3,
            'password' => 'sistema',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

    }
}
