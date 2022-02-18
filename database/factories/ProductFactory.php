<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sku' => $this->faker->unique()->word(),
            'name' => $this->faker->words(2, true),
            'qty' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->numberBetween(50, 200),
            'unit' => $this->faker->randomElement(['Unit', 'Carton', 'Pcs']),
            'status' => $this->faker->randomElement([0,1])
        ];
    }
}
