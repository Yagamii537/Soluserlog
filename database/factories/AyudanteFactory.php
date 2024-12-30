<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ayudante>
 */
class AyudanteFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'cedula' => $this->faker->unique()->numerify('##########'), // 10 dígitos
            'telefono' => $this->faker->phoneNumber(),
        ];
    }
}
