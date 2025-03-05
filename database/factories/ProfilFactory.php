<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profil>
 */
class ProfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lastname' => fake()->lastName(),
            'firstname' => fake()->firstName(),
            'status_id' => Status::inRandomOrder()->first()->id
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($profil) {
            $imageUrl = 'https://picsum.photos/200';
            $tempImage = tempnam(sys_get_temp_dir(), 'fake_image_');
            copy($imageUrl, $tempImage);
            
            $profil->addMedia(new File($tempImage))
                  ->toMediaCollection('avatar');
        });
    }
}
