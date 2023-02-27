<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @var string
     */
    protected $model = Customer::class;

    public function definition()
    {
        $type = $this->faker->randomElement(['I', 'B']);
        $name = $type == 'I' ? $this->faker->name() : $this->faker->company();
        return [
            'name' => $name,
            'type' => $type,
            'email' => fake()->unique()->safeEmail(),
            'address' => $this->faker->streetAddress(), // password
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postCode()
        ];
    }
}

/*$factory->define(Customer::class, function (Faker $faker) {
    return [
        //
    ];
});*/
