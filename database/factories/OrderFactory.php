<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'remitente' => $this->faker->name,
            'localidad' => $this->faker->city,
            'fechaCreacion' => Carbon::now()->startOfWeek()->addDays(rand(0, 6)),
            'fechaConfirmacion' => $this->faker->date,
            'horario' => $this->faker->time,
            'fechaEntrega' => $this->faker->date,
            'observacion' => $this->faker->sentence,
            'estado' => $this->faker->numberBetween(0, 1),
            'totaBultos' => $this->faker->numberBetween(1, 100),
            'totalKgr' => $this->faker->numberBetween(1, 1000),
            'cliente_id' => $this->faker->numberBetween(1, 20), // Valor aleatorio entre 1 y 10
        ];
    }
}
