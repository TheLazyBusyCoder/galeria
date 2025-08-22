<?php

namespace Database\Factories;

use App\Models\PhotoModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotoModel>
 */
class PhotoFactory extends Factory
{
    protected $model = PhotoModel::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id ?? 1,
            'image_path' => 'https://picsum.photos/640/480?random=' . $this->faker->unique()->numberBetween(1, 10000),
            'caption' => $this->faker->sentence(8),
        ];
    }
}
