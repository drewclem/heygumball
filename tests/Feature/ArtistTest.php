<?php

use App\Enums\ArtistRoleType;
use App\Models\Artist;
use App\Models\Client;
use App\Models\CustomField;
use App\Models\FormSection;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates ULID for artist', function () {
    $artist = Artist::factory()->create();

    expect($artist->id)->toHaveLength(26);
    expect($artist->id)->toMatch('/^[0-9A-Za-z]{26}$/');
});

it('belongs to user and organization', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create();
    $artist = Artist::factory()->create([
        'user_id' => $user->id,
        'organization_id' => $organization->id,
    ]);

    expect($artist->user)->toBeInstanceOf(User::class);
    expect($artist->user->id)->toBe($user->id);
    expect($artist->organization)->toBeInstanceOf(Organization::class);
    expect($artist->organization->id)->toBe($organization->id);
});

it('has many clients', function () {
    $artist = Artist::factory()->create();
    $clients = Client::factory()->count(3)->create(['artist_id' => $artist->id]);

    expect($artist->clients)->toHaveCount(3);
    expect($artist->clients->first())->toBeInstanceOf(Client::class);
});

it('has many custom fields ordered by sort order', function () {
    $artist = Artist::factory()->create();

    // Create a form without default sections for this test
    $artistForm = $artist->form()->create([
        'title' => 'Test Form',
        'is_active' => true,
    ]);

    $section = FormSection::factory()->create([
        'artist_form_id' => $artistForm->id,
        'title' => 'Test Section',
        'sort_order' => 0,
    ]);

    $field3 = CustomField::factory()->create([
        'form_section_id' => $section->id,
        'sort_order' => 3,
    ]);
    $field1 = CustomField::factory()->create([
        'form_section_id' => $section->id,
        'sort_order' => 1,
    ]);
    $field2 = CustomField::factory()->create([
        'form_section_id' => $section->id,
        'sort_order' => 2,
    ]);

    $customFields = $artist->getAllCustomFields();

    expect($customFields)->toHaveCount(3);
    expect($customFields[0]->id)->toBe($field1->id);
    expect($customFields[1]->id)->toBe($field2->id);
    expect($customFields[2]->id)->toBe($field3->id);
});

it('can find or create client by email', function () {
    $artist = Artist::factory()->create();

    // Create new client
    $client = $artist->findOrCreateClient('test@example.com', ['name' => 'Test Client']);

    expect($client)->toBeInstanceOf(Client::class);
    expect($client->email)->toBe('test@example.com');
    expect($client->name)->toBe('Test Client');
    expect($client->artist_id)->toBe($artist->id);

    // Find existing client
    $existingClient = $artist->findOrCreateClient('test@example.com', ['name' => 'Updated Name']);

    expect($existingClient->id)->toBe($client->id);
    expect($existingClient->name)->toBe('Test Client'); // Should not update existing
});

it('has route key name as username', function () {
    $artist = Artist::factory()->create();

    expect($artist->getRouteKeyName())->toBe('username');
});

it('generates booking URL', function () {
    $artist = Artist::factory()->create(['username' => 'sarah-ink']);

    expect($artist->booking_url)->toBe(url('/sarah-ink'));
});

describe('booking form status', function () {
    it('is open by default', function () {
        $artist = Artist::factory()->create();

        // Ensure the artist has a form
        $artist->getOrCreateForm();

        expect($artist->isBookingFormOpen())->toBeTrue();
    });

    it('can be manually closed', function () {
        $artist = Artist::factory()->create();

        $artist->closeBookingForm();

        expect($artist->fresh()->isBookingFormOpen())->toBeFalse();
    });

    it('can be manually opened', function () {
        $artist = Artist::factory()->create();
        $artist->closeBookingForm();

        $artist->openBookingForm();

        expect($artist->fresh()->isBookingFormOpen())->toBeTrue();
    });

    it('can be set to auto-close at specific time', function () {
        $artist = Artist::factory()->create();
        $closeTime = Carbon::now()->addHour();

        $artist->closeBookingFormAt($closeTime);

        expect($artist->fresh()->isBookingFormOpen())->toBeTrue();
        expect($artist->fresh()->form->auto_close_at->format('Y-m-d H:i:s'))->toBe($closeTime->format('Y-m-d H:i:s'));
    });

    it('automatically closes when auto-close time is reached', function () {
        $artist = Artist::factory()->create();
        $artist->closeBookingFormAt(Carbon::now()->subHour()); // Past time

        expect($artist->isBookingFormOpen())->toBeFalse();
    });

    it('stays open when auto-close time is in future', function () {
        $artist = Artist::factory()->create();
        $artist->closeBookingFormAt(Carbon::now()->addHour()); // Future time

        expect($artist->isBookingFormOpen())->toBeTrue();
    });
});

describe('artist factory states', function () {
    it('can create owner artist', function () {
        $artist = Artist::factory()->owner()->create();

        expect($artist->role)->toBe(ArtistRoleType::OWNER);
    });

    it('can create admin artist', function () {
        $artist = Artist::factory()->admin()->create();

        expect($artist->role)->toBe(ArtistRoleType::ADMIN);
    });

    it('can create artist with closed booking form', function () {
        $artist = Artist::factory()->create();
        $artist->closeBookingForm();

        expect($artist->isBookingFormOpen())->toBeFalse();
    });

    it('can create artist with auto-closing booking form', function () {
        $artist = Artist::factory()->create();
        $closeTime = Carbon::now()->addDays(7);
        $artist->closeBookingFormAt($closeTime);

        expect($artist->isBookingFormOpen())->toBeTrue();
        expect($artist->form->auto_close_at)->not->toBeNull();
        expect($artist->form->auto_close_at->isFuture())->toBeTrue();
    });
});
