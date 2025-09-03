<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
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
            'name' => fake()->words(2, true),
            'color' => fake()->hexColor(),
            'description' => fake()->optional(0.7)->sentence(),
        ];
    }

    /**
     * Create a style tag.
     */
    public function style(): static
    {
        $styles = [
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
            'Script',
            'Mandala',
            'Abstract',
            'Fine Line',
            'Dotwork',
        ];

        return $this->state(fn(array $attributes) => [
            'name' => fake()->randomElement($styles),
            'description' => fake()->optional(0.8)->sentence(),
        ]);
    }

    /**
     * Create a size tag.
     */
    public function size(): static
    {
        $sizes = [
            'Small',
            'Medium',
            'Large',
            'Extra Large',
            'Full Sleeve',
            'Half Sleeve',
            'Quarter Sleeve',
            'Back Piece',
            'Full Leg',
            'Half Leg',
        ];

        return $this->state(fn(array $attributes) => [
            'name' => fake()->randomElement($sizes),
            'description' => fake()->optional(0.6)->sentence(),
        ]);
    }

    /**
     * Create a status tag.
     */
    public function status(): static
    {
        $statuses = [
            'Rush',
            'Regular Client',
            'First Timer',
            'Returning Client',
            'VIP',
            'Referral',
            'Walk-in',
            'Appointment',
            'Consultation',
            'Touch-up',
        ];

        return $this->state(fn(array $attributes) => [
            'name' => fake()->randomElement($statuses),
            'description' => fake()->optional(0.5)->sentence(),
        ]);
    }

    /**
     * Create a placement tag.
     */
    public function placement(): static
    {
        $placements = [
            'Arm',
            'Leg',
            'Back',
            'Chest',
            'Shoulder',
            'Forearm',
            'Calf',
            'Ankle',
            'Wrist',
            'Ribcage',
            'Thigh',
            'Bicep',
            'Neck',
            'Hand',
            'Foot',
        ];

        return $this->state(fn(array $attributes) => [
            'name' => fake()->randomElement($placements),
            'description' => fake()->optional(0.6)->sentence(),
        ]);
    }

    /**
     * Create a color tag.
     */
    public function color(): static
    {
        $colors = [
            'Black & Gray',
            'Full Color',
            'Red',
            'Blue',
            'Green',
            'Purple',
            'Yellow',
            'Orange',
            'Pink',
            'Brown',
            'White',
            'Gold',
            'Silver',
        ];

        return $this->state(fn(array $attributes) => [
            'name' => fake()->randomElement($colors),
            'description' => fake()->optional(0.7)->sentence(),
        ]);
    }

    /**
     * Create a tag with a specific color.
     */
    public function withColor(string $color): static
    {
        return $this->state(fn(array $attributes) => [
            'color' => $color,
        ]);
    }

    /**
     * Create a tag for a specific artist.
     */
    public function forArtist(Artist $artist): static
    {
        return $this->state(fn(array $attributes) => [
            'artist_id' => $artist->id,
        ]);
    }

    /**
     * Create popular tags that artists commonly use.
     */
    public function popular(): static
    {
        $popularTags = [
            'Traditional' => '#dc2626',
            'Realism' => '#7c3aed',
            'Blackwork' => '#000000',
            'Watercolor' => '#ec4899',
            'Geometric' => '#059669',
            'Minimalist' => '#6b7280',
            'Portrait' => '#ea580c',
            'Script' => '#1e40af',
            'Japanese' => '#dc2626',
            'Tribal' => '#92400e',
        ];

        $tagName = fake()->randomElement(array_keys($popularTags));
        $tagColor = $popularTags[$tagName];

        return $this->state(fn(array $attributes) => [
            'name' => $tagName,
            'color' => $tagColor,
            'description' => fake()->optional(0.8)->sentence(),
        ]);
    }
}
