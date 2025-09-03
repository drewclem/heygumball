<?php

use App\Models\Artist;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates ULID for client', function () {
    $client = Client::factory()->create();

    expect($client->id)->toHaveLength(26);
    expect($client->id)->toMatch('/^[0-9A-Za-z]{26}$/');
});

it('belongs to artist', function () {
    $artist = Artist::factory()->create();
    $client = Client::factory()->create(['artist_id' => $artist->id]);

    expect($client->artist)->toBeInstanceOf(Artist::class);
    expect($client->artist->id)->toBe($artist->id);
});

it('can access artist through relationship', function () {
    $artist = Artist::factory()->create();
    $client = Client::factory()->create(['artist_id' => $artist->id]);

    expect($client->artist->username)->toBe($artist->username);
    expect($client->artist->display_name)->toBe($artist->display_name);
});

it('maintains unique email per artist', function () {
    $artist1 = Artist::factory()->create();
    $artist2 = Artist::factory()->create();

    // Create client for first artist
    Client::factory()->create([
        'artist_id' => $artist1->id,
        'email' => 'test@example.com',
    ]);

    // Create client for second artist with same email (should work)
    $client2 = Client::factory()->create([
        'artist_id' => $artist2->id,
        'email' => 'test@example.com',
    ]);

    expect($client2->email)->toBe('test@example.com');
    expect($client2->artist_id)->toBe($artist2->id);

    // Attempt to create another client for first artist with same email (should fail)
    expect(function () use ($artist1) {
        Client::factory()->create([
            'artist_id' => $artist1->id,
            'email' => 'test@example.com',
        ]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});

it('can have nullable fields', function () {
    $client = Client::factory()->create([
        'phone' => null,
        'date_of_birth' => null,
        'emergency_contact_name' => null,
        'emergency_contact_phone' => null,
        'notes' => null,
    ]);

    expect($client->phone)->toBeNull();
    expect($client->date_of_birth)->toBeNull();
    expect($client->emergency_contact_name)->toBeNull();
    expect($client->emergency_contact_phone)->toBeNull();
    expect($client->notes)->toBeNull();
});

it('casts date_of_birth to date', function () {
    $client = Client::factory()->create([
        'date_of_birth' => '1990-01-15',
    ]);

    expect($client->date_of_birth)->toBeInstanceOf(\Carbon\Carbon::class);
    expect($client->date_of_birth->format('Y-m-d'))->toBe('1990-01-15');
});

it('can be created with all required fields', function () {
    $client = Client::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    expect($client->name)->toBe('John Doe');
    expect($client->email)->toBe('john@example.com');
});

it('can be created with optional fields', function () {
    $client = Client::factory()->create([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'phone' => '555-123-4567',
        'date_of_birth' => '1985-06-20',
        'emergency_contact_name' => 'Bob Smith',
        'emergency_contact_phone' => '555-987-6543',
        'notes' => 'Prefers afternoon appointments',
    ]);

    expect($client->name)->toBe('Jane Smith');
    expect($client->email)->toBe('jane@example.com');
    expect($client->phone)->toBe('555-123-4567');
    expect($client->date_of_birth->format('Y-m-d'))->toBe('1985-06-20');
    expect($client->emergency_contact_name)->toBe('Bob Smith');
    expect($client->emergency_contact_phone)->toBe('555-987-6543');
    expect($client->notes)->toBe('Prefers afternoon appointments');
});

describe('client factory states', function () {
    it('can create minor client', function () {
        $client = Client::factory()->minor()->create();

        expect($client->date_of_birth)->not->toBeNull();
        $age = $client->date_of_birth->age;
        expect($age)->toBeGreaterThanOrEqual(16);
        expect($age)->toBeLessThanOrEqual(17);
    });

    it('can create elderly client', function () {
        $client = Client::factory()->elderly()->create();

        expect($client->date_of_birth)->not->toBeNull();
        $age = $client->date_of_birth->age;
        expect($age)->toBeGreaterThanOrEqual(65);
        expect($age)->toBeLessThanOrEqual(80);
    });

    it('can create client without emergency contact', function () {
        $client = Client::factory()->noEmergencyContact()->create();

        expect($client->emergency_contact_name)->toBeNull();
        expect($client->emergency_contact_phone)->toBeNull();
    });
});
