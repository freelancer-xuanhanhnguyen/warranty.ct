<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->bothify('KH###??')),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'birthday' => $this->faker->date('Y-m-d', '-18 years'),
            'gender' => $this->faker->randomElement([0, 1, 2]), // 0: Other, 1: Male, 2: Female
            'address' => $this->faker->address,
        ];
    }
}
