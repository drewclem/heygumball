<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArtistForm>
 */
class ArtistFormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $artist = Artist::factory()->create();

        return [
            'artist_id' => $artist->id,
            'title' => "{$artist->display_name}'s Booking Form",
            'welcome_message' => fake()->randomElement([
                "Thanks for your interest in getting tattooed! Please fill out this form with as much detail as possible.",
                "Welcome! I'm excited to work with you on your tattoo. Please share your vision below.",
                "Ready to create something amazing together? Fill out this form and let's get started!",
                "Thank you for choosing me for your tattoo journey. Please provide as much detail as possible.",
            ]),
            'submission_success_message' => fake()->randomElement([
                "Thank you! I'll review your submission and get back to you within 48 hours.",
                "Perfect! I've received your request and will review it carefully. Expect a response within 2 business days.",
                "Thanks for reaching out! I'll get back to you soon with next steps.",
                "Great! Your submission has been received. I'll review it and contact you within 48 hours.",
            ]),
            'is_active' => true,
        ];
    }

    /**
     * Create an inactive form.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a form with custom title.
     */
    public function withTitle(string $title): static
    {
        return $this->state(fn(array $attributes) => [
            'title' => $title,
        ]);
    }

    /**
     * Create a form with custom welcome message.
     */
    public function withWelcomeMessage(string $message): static
    {
        return $this->state(fn(array $attributes) => [
            'welcome_message' => $message,
        ]);
    }

    /**
     * Create a form with custom success message.
     */
    public function withSuccessMessage(string $message): static
    {
        return $this->state(fn(array $attributes) => [
            'submission_success_message' => $message,
        ]);
    }

    /**
     * Create a form for a specific artist.
     */
    public function forArtist(Artist $artist): static
    {
        return $this->state(fn(array $attributes) => [
            'artist_id' => $artist->id,
            'title' => "{$artist->display_name}'s Booking Form",
        ]);
    }
}
