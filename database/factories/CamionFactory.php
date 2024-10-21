<?php

namespace Database\Factories;
use App\Models\Camion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Camion>
 */
class CamionFactory extends Factory
{
    protected $model = Camion::class;

    public function definition()
    {
        return [
            'numero_placa' => $this->faker->unique()->bothify('???-####'),  // Placa aleatoria
            'modelo' => $this->faker->randomElement(['Volvo', 'Mercedes', 'Scania', 'Iveco']),
            'marca' => $this->faker->company,
            'capacidad_carga' => $this->faker->numberBetween(1000, 25000),  // Capacidad en kg
        ];
    }
}
