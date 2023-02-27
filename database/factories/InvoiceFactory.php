<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Invoice;
use App\Customer;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @var string
     */
    protected $model = Invoice::class;

    public function definition()
    {
        $status = $this->faker->randomElement(['B', 'P', 'V']);
        
        return [
            'customer_id' => Customer::factory(),
            'amount' => $this->faker->numberBetween(100, 20000),
            'status' => $status,
            'billed_date' => $this->faker->dateTimeThisDecade(), // password
            'paid_date' => $status == 'P' ? $this->faker->dateTimeThisDecade() : NULL
        ];
    }
}

/*$factory->define(Invoice::class, function (Faker $faker) {
    return [
        //
    ];
});*/
