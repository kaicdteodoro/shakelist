<?php

namespace Database\Factories;

use App\Models\Queue;
use App\Models\QueueMusic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QueueMusic>
 */
class QueueMusicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'queue_id' => $this->faker->randomNumber(),
            'reference_name' => $this->faker->name,
            'url' => $this->faker->url()
        ];
    }
}
