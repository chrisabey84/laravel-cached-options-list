<?php

namespace Chrisabey84\LaravelCachedOptionsList\Tests\Stubs\Factories;

use Chrisabey84\LaravelCachedOptionsList\Tests\Stubs\Models\TestModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestModelFactory extends Factory
{
    protected $model = TestModel::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
        ];
    }
}
