<?php

namespace Database\Factories;

use App\Models\Conductor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conductor>
 */
class ConductorFactory extends Factory
{
    protected $model = Conductor::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->name,  // Asegúrate de que se genera un nombre
            'numero_licencia' => $this->faker->unique()->bothify('??-########'),  // Número de licencia aleatorio
            'telefono' => $this->faker->phoneNumber,  // Número de teléfono aleatorio
        ];
    }
}
