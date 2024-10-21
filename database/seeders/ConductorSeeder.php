<?php

namespace Database\Seeders;

use App\Models\Conductor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConductorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear 10 conductores usando el factory
        Conductor::factory(10)->create();
    }
}
