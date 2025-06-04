<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'code' => strtoupper($this->faker->bothify('PRD###??')),
            'serial' => strtoupper($this->faker->bothify('SN####??')),
            'warranty_period_unit' => $this->faker->randomElement([Product::WARRANTY_UNIT_DAY, Product::WARRANTY_UNIT_MONTH, Product::WARRANTY_UNIT_YEAR]),
            'warranty_period' => $this->faker->numberBetween(6, 36),
            'periodic_warranty_unit' => $this->faker->randomElement([Product::WARRANTY_UNIT_DAY, Product::WARRANTY_UNIT_MONTH, Product::WARRANTY_UNIT_YEAR]),
            'periodic_warranty' => $this->faker->numberBetween(1, 12),
            'repairman_id' => User::inRandomOrder()->value('id'),
        ];
    }
}
