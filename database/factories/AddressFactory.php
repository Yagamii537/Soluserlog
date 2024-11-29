<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Address::class;

    public function definition()
    {
        return [
            'cliente_id' => Cliente::factory(), // Asigna una relación con un cliente
            'nombre_sucursal' => $this->faker->companySuffix, // Nombre opcional de la sucursal
            'ciudad' => $this->faker->city,
            'provincia' => $this->faker->state,
            'direccion' => $this->faker->streetAddress,
            'latitud' => $this->faker->latitude(-3.5, 1.5),
            'longitud' => $this->faker->longitude(-81.0, -75.0),
            'zona' => $this->faker->word,
        ];
    }
}
