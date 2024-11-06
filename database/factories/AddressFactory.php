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
            'direccion' => $this->faker->streetAddress,
            'ciudad' => $this->faker->city,
            'provincia' => $this->faker->state,
            'zona' => $this->faker->word,
        ];
    }
}
