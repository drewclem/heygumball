<?php

namespace Database\Factories;

use App\Models\Artist;
use App\Models\Client;
use App\Enums\SubmissionStatusType;
use Database\Factories\ArtistFactory;
use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $artist = ArtistFactory::new()->create();
        $client = ClientFactory::new()->create(['artist_id' => $artist->id]);

        // Ensure the artist has a form
        $artistForm = $artist->getOrCreateForm();

        return [
            'artist_id' => $artist->id,
            'client_id' => $client->id,
            'artist_form_id' => $artistForm->id,
            'status' => fake()->randomElement([
                SubmissionStatusType::PENDING,
                SubmissionStatusType::APPROVED,
                SubmissionStatusType::UNDER_REVIEW,
                SubmissionStatusType::REJECTED,
                SubmissionStatusType::SCHEDULED,
                SubmissionStatusType::COMPLETED,
                SubmissionStatusType::CANCELLED,
            ]),
            'artist_notes' => fake()->optional(0.6)->paragraph(),
            'estimated_duration' => fake()->randomElement([60, 90, 120, 180, 240, 300, 360, 480]), // minutes
            'estimated_price' => fake()->randomFloat(2, 150, 2000),
            'submitted_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'reviewed_at' => fake()->optional(0.7)->dateTimeBetween('-3 months', 'now'),
            'client_name' => $client->name,
            'client_email' => $client->email,
            'client_phone' => $client->phone,
        ];
    }

    /**
     * Create a pending submission.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => SubmissionStatusType::PENDING,
            'reviewed_at' => null,
            'rejection_reason' => null,
            'artist_notes' => null,
        ]);
    }

    /**
     * Create an approved submission.
     */
    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => SubmissionStatusType::APPROVED,
            'reviewed_at' => fake()->dateTimeBetween('-2 months', 'now'),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Create a rejected submission.
     */
    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => SubmissionStatusType::REJECTED,
            'reviewed_at' => fake()->dateTimeBetween('-2 months', 'now'),
            'rejection_reason' => fake()->sentence(),
        ]);
    }

    /**
     * Create a completed submission.
     */
    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => SubmissionStatusType::COMPLETED,
            'reviewed_at' => fake()->dateTimeBetween('-3 months', '-1 month'),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Create a submission with specific artist and client.
     */
    public function forArtistAndClient(Artist $artist, Client $client): static
    {
        return $this->state(fn(array $attributes) => [
            'artist_id' => $artist->id,
            'client_id' => $client->id,
            'client_name' => $client->name,
            'client_email' => $client->email,
            'client_phone' => $client->phone,
        ]);
    }

    /**
     * Create a submission with realistic tattoo details.
     */
    public function realistic(): static
    {
        $tattooTypes = [
            'Traditional sleeve design',
            'Minimalist geometric pattern',
            'Realistic portrait',
            'Japanese style back piece',
            'Blackwork forearm design',
            'Watercolor floral piece',
            'Tribal armband',
            'Script text on wrist',
            'Animal design on shoulder',
            'Abstract line work',
        ];

        return $this->state(fn(array $attributes) => [
            'artist_notes' => fake()->optional(0.7)->randomElement([
                'Great reference photos provided',
                'Client has clear vision',
                'Complex design, may need multiple sessions',
                'Simple design, should be straightforward',
                'Client is flexible with design changes',
                'Rush job requested',
                'Client prefers specific color palette',
                'May need to adjust size for placement',
            ]),
            'estimated_duration' => fake()->randomElement([60, 90, 120, 180, 240, 300, 360, 480]),
            'estimated_price' => fake()->randomFloat(2, 150, 2000),
        ]);
    }
}
