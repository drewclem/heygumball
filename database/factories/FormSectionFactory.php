<?php

namespace Database\Factories;

use App\Models\ArtistForm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormSection>
 */
class FormSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'artist_form_id' => fn() => ArtistFormFactory::new()->create(),
            'title' => fake()->randomElement([
                'Tattoo Details',
                'Experience & Medical Information',
                'Scheduling Preferences',
                'Design Preferences',
                'Size & Placement',
                'Budget & Timeline',
                'Reference Materials',
                'Special Requirements',
            ]),
            'description' => fake()->optional(0.8)->sentence(),
            'icon' => fake()->randomElement([
                'pencil',
                'heart',
                'calendar',
                'star',
                'document-text',
                'photograph',
                'cog',
                'lightning-bolt',
            ]),
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }

    /**
     * Create an inactive section.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a section with specific title.
     */
    public function withTitle(string $title): static
    {
        return $this->state(fn(array $attributes) => [
            'title' => $title,
        ]);
    }

    /**
     * Create a section with specific icon.
     */
    public function withIcon(string $icon): static
    {
        return $this->state(fn(array $attributes) => [
            'icon' => $icon,
        ]);
    }

    /**
     * Create a section with specific sort order.
     */
    public function withSortOrder(int $sortOrder): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $sortOrder,
        ]);
    }

    /**
     * Create a section for a specific artist form.
     */
    public function forArtistForm(ArtistForm $artistForm): static
    {
        return $this->state(fn(array $attributes) => [
            'artist_form_id' => $artistForm->id,
        ]);
    }

    /**
     * Create default tattoo details section.
     */
    public function tattooDetails(): static
    {
        return $this->state(fn(array $attributes) => [
            'title' => 'Tattoo Details',
            'description' => 'Tell us about your tattoo idea',
            'icon' => 'pencil',
            'sort_order' => 0,
        ]);
    }

    /**
     * Create default experience & medical section.
     */
    public function experienceMedical(): static
    {
        return $this->state(fn(array $attributes) => [
            'title' => 'Experience & Medical Information',
            'description' => 'Help us understand your experience and any medical considerations',
            'icon' => 'heart',
            'sort_order' => 1,
        ]);
    }

    /**
     * Create default scheduling section.
     */
    public function scheduling(): static
    {
        return $this->state(fn(array $attributes) => [
            'title' => 'Scheduling Preferences',
            'description' => 'Let us know your availability and preferences',
            'icon' => 'calendar',
            'sort_order' => 2,
        ]);
    }
}
