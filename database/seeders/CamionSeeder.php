<?php

namespace Database\Seeders;

use App\Models\Camion;
use App\Models\Conductor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CamionSeeder extends Seeder
{
    public function run()
    {
        // Crear 10 camiones
        $camiones = Camion::factory(10)->create();

        // Asignar aleatoriamente conductores a cada camión
        $conductores = Conductor::all();

        foreach ($camiones as $camion) {
            // Asignar entre 1 y 3 conductores a cada camión
            $camion->conductores()->attach(
                $conductores->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
