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
     * Format price.
     *
     * @param   int  $price
     *
     * @return  int
     */
    private function formatPrice(int $price)
    {
        $prefix = substr($price, 0, 2);
        $count = count(str_split(substr($price, 2)));
        $rest = str_repeat('0', $count);
        $final = $prefix . $rest;

        return (int) $final;
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(rand(1, 5), true),
            'stock' => rand(0, 30),
            'price' => $this->formatPrice(rand(500, 500000)),
        ];
    }
}
