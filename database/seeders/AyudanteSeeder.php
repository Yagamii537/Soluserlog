<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ayudante;

class AyudanteSeeder extends Seeder
{
    public function run()
    {
        // Crear 50 ayudantes de prueba
        Ayudante::factory()->count(10)->create();
    }
}
