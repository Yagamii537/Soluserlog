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
            'estado' => $this->faker->randomElement([0, 1]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    // Configura la relación para que genere automáticamente direcciones
    public function withAddresses()
    {
        return $this->has(
            \App\Models\Address::factory()->count(rand(1, 2)),
            'addresses'
        );
    }
}
