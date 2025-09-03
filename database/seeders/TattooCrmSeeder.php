<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Artist;
use App\Models\Client;
use App\Models\Submission;
use App\Models\CustomField;
use Illuminate\Database\Seeder;
use Database\Factories\TagFactory;
use Database\Factories\UserFactory;
use Database\Factories\ArtistFactory;
use Database\Factories\ClientFactory;
use Database\Factories\SubmissionFactory;
use App\Models\SubmissionCustomFieldEntry;
use Database\Factories\OrganizationFactory;
use Database\Factories\SubmissionCustomFieldEntryFactory;

class TattooCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test organization
        $organization = OrganizationFactory::new()->create([
            'name' => 'Ink Masters Studio',
        ]);

        // Create owner user and artist
        $ownerUser = UserFactory::new()->create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@inkmasters.com',
        ]);

        $ownerArtist = ArtistFactory::new()->owner()->create([
            'user_id' => $ownerUser->id,
            'organization_id' => $organization->id,
            'username' => 'sarah-ink',
            'display_name' => 'Sarah Johnson',
            'bio' => 'Master tattoo artist with 15+ years of experience specializing in traditional and neo-traditional styles.',
            'specialties' => ['Traditional', 'Neo-traditional', 'Japanese'],
            'hourly_rate' => 250.00,
            'joined_at' => now()->subYears(2),
        ]);

        // Create admin user and artist
        $adminUser = UserFactory::new()->create([
            'name' => 'Mike Rodriguez',
            'email' => 'mike@inkmasters.com',
        ]);

        $adminArtist = ArtistFactory::new()->admin()->create([
            'user_id' => $adminUser->id,
            'organization_id' => $organization->id,
            'username' => 'mike-traditional',
            'display_name' => 'Mike Rodriguez',
            'bio' => 'Expert in traditional American and blackwork styles. Passionate about classic designs.',
            'specialties' => ['Traditional', 'Blackwork', 'Tribal'],
            'hourly_rate' => 200.00,
            'joined_at' => now()->subYear(),
        ]);

        // Create regular artist
        $artistUser = UserFactory::new()->create([
            'name' => 'Jessica Chen',
            'email' => 'jessica@inkmasters.com',
        ]);

        $regularArtist = ArtistFactory::new()->create([
            'user_id' => $artistUser->id,
            'organization_id' => $organization->id,
            'username' => 'jessica-minimal',
            'display_name' => 'Jessica Chen',
            'bio' => 'Specializing in minimalist and geometric designs. Clean lines and precise work.',
            'specialties' => ['Minimalist', 'Geometric', 'Fine Line'],
            'hourly_rate' => 180.00,
            'joined_at' => now()->subMonths(6),
        ]);

        // Create artist forms with default sections for each artist
        $this->createArtistFormForArtist($ownerArtist);
        $this->createArtistFormForArtist($adminArtist);
        $this->createArtistFormForArtist($regularArtist);

        // Create clients for each artist
        $this->createClientsForArtist($ownerArtist, 10);
        $this->createClientsForArtist($adminArtist, 8);
        $this->createClientsForArtist($regularArtist, 6);

        // Create some artists with closed booking forms
        ArtistFactory::new()->closedBookingForm()->count(2)->create([
            'organization_id' => $organization->id,
        ]);

        // Create some artists with auto-closing forms
        ArtistFactory::new()->autoClosingBookingForm()->count(1)->create([
            'organization_id' => $organization->id,
        ]);

        // Create tags for each artist
        $this->createTagsForArtist($ownerArtist);
        $this->createTagsForArtist($adminArtist);
        $this->createTagsForArtist($regularArtist);

        // Create submissions for each artist
        $this->createSubmissionsForArtist($ownerArtist, 15);
        $this->createSubmissionsForArtist($adminArtist, 12);
        $this->createSubmissionsForArtist($regularArtist, 8);
    }

    /**
     * Create artist form with default sections for a specific artist.
     */
    private function createArtistFormForArtist(Artist $artist): void
    {
        // Use the built-in service to create the default form
        $artist->getOrCreateForm();
    }



    /**
     * Create clients for a specific artist.
     */
    private function createClientsForArtist(Artist $artist, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            ClientFactory::new()->create([
                'artist_id' => $artist->id,
                'email' => 'client' . ($i + 1) . '@' . $artist->username . '.com',
            ]);
        }
    }

    /**
     * Create tags for a specific artist.
     */
    private function createTagsForArtist(Artist $artist): void
    {
        // Create style tags with specific names
        $styleTags = ['Traditional', 'Realism', 'Blackwork', 'Watercolor', 'Geometric'];
        foreach (array_slice($styleTags, 0, 3) as $style) {
            TagFactory::new()->create([
                'artist_id' => $artist->id,
                'name' => $style,
                'color' => fake()->hexColor(),
                'description' => fake()->optional(0.8)->sentence(),
            ]);
        }

        // Create size tags with specific names
        $sizeTags = ['Small', 'Medium', 'Large', 'Extra Large'];
        foreach (array_slice($sizeTags, 0, 2) as $size) {
            TagFactory::new()->create([
                'artist_id' => $artist->id,
                'name' => $size,
                'color' => fake()->hexColor(),
                'description' => fake()->optional(0.6)->sentence(),
            ]);
        }

        // Create status tags with specific names
        $statusTags = ['Rush', 'Regular Client', 'First Timer', 'VIP'];
        foreach (array_slice($statusTags, 0, 2) as $status) {
            TagFactory::new()->create([
                'artist_id' => $artist->id,
                'name' => $status,
                'color' => fake()->hexColor(),
                'description' => fake()->optional(0.5)->sentence(),
            ]);
        }

        // Create placement tags with specific names
        $placementTags = ['Arm', 'Leg', 'Back', 'Shoulder', 'Forearm'];
        foreach (array_slice($placementTags, 0, 2) as $placement) {
            TagFactory::new()->create([
                'artist_id' => $artist->id,
                'name' => $placement,
                'color' => fake()->hexColor(),
                'description' => fake()->optional(0.6)->sentence(),
            ]);
        }

        // Create color tags with specific names
        $colorTags = ['Black & Gray', 'Full Color', 'Red', 'Blue', 'Green'];
        foreach (array_slice($colorTags, 0, 2) as $color) {
            TagFactory::new()->create([
                'artist_id' => $artist->id,
                'name' => $color,
                'color' => fake()->hexColor(),
                'description' => fake()->optional(0.7)->sentence(),
            ]);
        }
    }

    /**
     * Create submissions for a specific artist.
     */
    private function createSubmissionsForArtist(Artist $artist, int $count): void
    {
        $clients = $artist->clients;

        for ($i = 0; $i < $count; $i++) {
            $client = $clients->random();

            $submission = SubmissionFactory::new()->create([
                'artist_id' => $artist->id,
                'client_id' => $client->id,
                'client_name' => $client->name,
                'client_email' => $client->email,
                'client_phone' => $client->phone,
            ]);

            // Create custom field entries for this submission
            $this->createCustomFieldEntriesForSubmission($submission, $artist);

            // Tag the submission
            $this->tagSubmission($submission, $artist);
        }
    }

    /**
     * Create custom field entries for a submission.
     */
    private function createCustomFieldEntriesForSubmission(Submission $submission, Artist $artist): void
    {
        // Get all custom fields from the artist's form sections
        $customFields = $artist->getAllCustomFields();

        foreach ($customFields as $customField) {
            SubmissionCustomFieldEntryFactory::new()->create([
                'submission_id' => $submission->id,
                'custom_field_id' => $customField->id,
                'value' => $this->generateValueForCustomField($customField),
                'file_path' => $customField->field_type->value === 'file' ? 'submissions/' . $submission->id . '/reference.jpg' : null,
                'file_original_name' => $customField->field_type->value === 'file' ? 'reference.jpg' : null,
            ]);
        }
    }

    /**
     * Generate realistic value for a custom field.
     */
    private function generateValueForCustomField(CustomField $customField): string
    {
        return match ($customField->field_type->value) {
            'text' => fake()->words(3, true),
            'textarea' => fake()->randomElement([
                'Traditional Japanese dragon sleeve on left arm, full color with red and gold accents',
                'Minimalist geometric pattern on right forearm, black ink only, clean lines',
                'Realistic portrait of my grandmother on upper back, black and gray shading',
                'Watercolor floral piece on right shoulder, soft pastel colors, organic feel',
                'Blackwork tribal design on left calf, bold geometric patterns',
            ]),
            'select', 'radio' => fake()->randomElement(['small', 'medium', 'large', 'under-100', '100-300', '300-500']),
            'checkbox' => fake()->boolean() ? '1' : '0',
            'file' => 'reference_photo.jpg',
            default => fake()->sentence(),
        };
    }

    /**
     * Tag a submission with relevant tags.
     */
    private function tagSubmission(Submission $submission, Artist $artist): void
    {
        $tags = $artist->tags;

        if ($tags->count() > 0) {
            // Tag with 1-3 random tags
            $randomTags = $tags->random(min(3, $tags->count()));
            $submission->tags()->attach($randomTags->pluck('id'));
        }
    }
}
