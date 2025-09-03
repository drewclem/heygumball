<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'artist_id' => fn() => ArtistFactory::new()->create(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'date_of_birth' => fake()->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'notes' => fake()->optional(0.7)->paragraph(),
        ];
    }

    /**
     * Indicate that the client is a minor (for testing purposes).
     */
    public function minor(): static
    {
        return $this->state(fn(array $attributes) => [
            'date_of_birth' => fake()->dateTimeBetween('-17 years', '-16 years')->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the client is elderly.
     */
    public function elderly(): static
    {
        return $this->state(fn(array $attributes) => [
            'date_of_birth' => fake()->dateTimeBetween('-80 years', '-65 years')->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the client has no emergency contact.
     */
    public function noEmergencyContact(): static
    {
        return $this->state(fn(array $attributes) => [
            'emergency_contact_name' => null,
            'emergency_contact_phone' => null,
        ]);
    }
}
