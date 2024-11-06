<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Address;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'remitente_direccion_id' => Address::factory()->create()->id,
            'destinatario_direccion_id' => Address::factory()->create()->id,
            'fechaCreacion' => $this->faker->date(),
            'fechaConfirmacion' => $this->faker->optional()->date(),
            'horario' => $this->faker->time,
            'fechaEntrega' => $this->faker->date(),
            'observacion' => $this->faker->sentence(),
            'estado' => $this->faker->randomElement([0, 1]),
            'totaBultos' => $this->faker->numberBetween(1, 100),
            'totalKgr' => $this->faker->numberBetween(1, 1000),
        ];
    }
}
