<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->address,
            'telephone' => $this->faker->phoneNumber,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Profile $profile) {
            Order::factory(rand(0, 20))->create([
                'user_id' => $profile->user->id,
            ]);
        });
    }
}
