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
            'razonSocial' => $this->faker->company(),  // Genera un nombre de empresa falso
            'ruc' => $this->faker->unique()->numerify('##########'),  // Genera un número de RUC ficticio
            'localidad' => $this->faker->city(),  // Genera una localidad ficticia
            'direccion' => $this->faker->address(),  // Genera una dirección falsa
            'pisos' => $this->faker->numberBetween(1, 50),  // Genera un número de pisos entre 1 y 50
            'CodigoPostal' => $this->faker->postcode(),  // Genera un código postal falso
            'ampliado' => $this->faker->sentence(3),  // Genera un texto corto de 3 palabras
            'celular' => $this->faker->phoneNumber(),  // Genera un número de celular falso
            'telefono' => $this->faker->phoneNumber(),  // Genera un número de teléfono falso
            'correo' => $this->faker->unique()->safeEmail(),  // Genera un correo electrónico único
            'contribuyente' => $this->faker->randomElement(['Si', 'No']),  // Genera una opción aleatoria entre "Si" y "No"
            'latitud' => $this->faker->latitude(),  // Genera una latitud falsa
            'longitud' => $this->faker->longitude(),  // Genera una longitud falsa
        ];
    }
}
