<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artist>
 */
class ArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialties = [
            'Traditional',
            'Realism',
            'Blackwork',
            'Neo-traditional',
            'Japanese',
            'Tribal',
            'Watercolor',
            'Geometric',
            'Minimalist',
            'Portrait',
        ];

        $displayName = fake()->firstName() . ' ' . fake()->lastName();
        $username = strtolower(str_replace(' ', '-', $displayName)) . '-' . fake()->randomElement(['ink', 'tattoo', 'art', 'design']);

        return [
            'user_id' => fn() => UserFactory::new()->create(),
            'organization_id' => fn() => OrganizationFactory::new()->create(),
            'username' => $username,
            'display_name' => $displayName,
            'bio' => fake()->paragraph(3),
            'specialties' => fake()->randomElements($specialties, fake()->numberBetween(1, 4)),
            'hourly_rate' => fake()->randomFloat(2, 100, 300),
            'role' => fake()->randomElement(['owner', 'admin', 'artist']),
            'status' => 'active',
            'joined_at' => fake()->dateTimeBetween('-2 years', 'now'),
            'exited_at' => null,
        ];
    }

    /**
     * Indicate that the artist is an owner.
     */
    public function owner(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'owner',
        ]);
    }

    /**
     * Indicate that the artist is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the artist has a closed booking form.
     */
    public function closedBookingForm(): static
    {
        return $this->state(fn(array $attributes) => [
            // This will be handled after creation by calling closeBookingForm()
        ]);
    }

    /**
     * Indicate that the artist has an auto-closing booking form.
     */
    public function autoClosingBookingForm(): static
    {
        return $this->state(fn(array $attributes) => [
            // This will be handled after creation by calling closeBookingFormAt()
        ]);
    }
}
