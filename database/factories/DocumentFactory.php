<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Document;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        return [
            'n_documento' => $this->faker->unique()->numerify('DOC-#####'),
            'tipo_carga' => $this->faker->word,
            'cantidad_bultos' => $this->faker->numberBetween(1, 100),
            'cantidad_kg' => $this->faker->randomFloat(2, 0.5, 1000),
            'factura' => $this->faker->unique()->numerify('FAC-#####'),
            'observaciones' => $this->faker->sentence,
            'order_id' => Order::inRandomOrder()->first()->id, // Asignar un order_id existente
        ];
    }
}
