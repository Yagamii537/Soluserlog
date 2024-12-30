<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AyudanteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->name,
            'cedula' => $this->faker->unique()->numerify('##########'), // 10 dígitos para la cédula
            'telefono' => $this->faker->phoneNumber,
            'foto' => 'ayudantes/' . $this->faker->image('storage/app/public/ayudantes', 150, 150, null, false), // Generar una imagen de prueba
        ];
    }
}
