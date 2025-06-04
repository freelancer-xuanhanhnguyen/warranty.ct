<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceStatusFactory extends Factory
{
    protected $model = ServiceStatus::class;

    public function definition(): array
    {
        return [
            'service_id' => Service::inRandomOrder()->value('id'),
            'code' => $this->faker->randomElement(array_keys(ServiceStatus::STATUS)),
        ];
    }
}
