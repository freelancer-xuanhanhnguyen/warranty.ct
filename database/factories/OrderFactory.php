<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $purchaseDate = $this->faker->date;
        return [
            'product_id' => Product::inRandomOrder()->value('id'),
            'customer_id' => Customer::inRandomOrder()->value('id'),
            'code' => strtoupper($this->faker->bothify('#####??')),
            'purchase_date' => $purchaseDate,
            'warranty_expired' => date('Y-m-d', strtotime("+1 year", strtotime($purchaseDate))),
        ];
    }
}
