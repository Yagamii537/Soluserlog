<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
    {
        return [
            'codigoCliente' => $this->faker->unique()->numberBetween(10000, 99999),
            'ruc' => $this->faker->unique()->numerify('#############'),
            'razonSocial' => $this->faker->company(),
            'tipoInstitucion' => $this->faker->randomElement(['Pública', 'Privada', 'ONG']),
            'tipoCliente' => $this->faker->randomElement(['Regular', 'Premium', 'Nuevo']),
            'publicoPrivado' => $this->faker->randomElement(['Público', 'Privado']),
            'telefono' => $this->faker->numerify('0##########'),
            'correo' => $this->faker->unique()->safeEmail(),
            'fechaCreacion' => $this->faker->date(),
            'latitud' => $this->faker->latitude(-3.5, 1.5),
            'longitud' => $this->faker->longitude(-81.0, -75.0),
            'estado' => $this->faker->randomElement([0, 1]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
