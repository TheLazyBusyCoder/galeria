<?php

namespace Database\Factories;

use App\Models\PhotoModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhotoModel>
 */
class PhotoFactory extends Factory
{
    protected $model = PhotoModel::class;

    public function definition(): array
    {
        // Get all files from the folder
        $files = File::files(public_path('storage/uploads/photos'));

        // Pick one random file
        $randomFile = collect($files)->random();

        // Make relative path (so it works with asset())
        $relativePath = 'uploads/photos/' . $randomFile->getFilename();

        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id ?? 1,
            'image_path' => $relativePath,
            'caption' => $this->faker->sentence(8),
        ];
    }
}