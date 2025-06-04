<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->value('id'),
            'repairman_id' => User::inRandomOrder()->value('id'),
            'code' => strtoupper($this->faker->bothify('SRV###??')),
            'type' => $this->faker->randomElement([Service::TYPE_REPAIR, Service::TYPE_WARRANTY]),
            'content' => $this->faker->paragraph,
            'fee_total' => $this->faker->randomFloat(2, 100, 1000),
            'fee_detail' => $this->faker->text(100),
            'reception_date' => $this->faker->date,
            'expected_completion_date' => $this->faker->date,
            'evaluate' => $this->faker->randomElement([0, 1, 2, 3, 4, 5]),
            'evaluate_note' => $this->faker->sentence,
        ];
    }
}
