<?php

use App\Models\Artist;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

it('binds artist by username in routes', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create();
    $artist = Artist::factory()->create([
        'user_id' => $user->id,
        'organization_id' => $organization->id,
        'username' => 'sarah-ink',
    ]);

    // Test that the route binding works correctly
    $boundArtist = app('router')->getBindingCallback('artist')('sarah-ink');

    expect($boundArtist)->toBeInstanceOf(Artist::class);
    expect($boundArtist->id)->toBe($artist->id);
    expect($boundArtist->username)->toBe('sarah-ink');
});

it('returns 404 for non-existent username', function () {
    // Test that the route binding throws an exception for non-existent username
    expect(function () {
        app('router')->getBindingCallback('artist')('non-existent-username');
    })->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

it('can be used in route parameters', function () {
    $user = User::factory()->create();
    $organization = Organization::factory()->create();
    $artist = Artist::factory()->create([
        'user_id' => $user->id,
        'organization_id' => $organization->id,
        'username' => 'mike-traditional',
    ]);

    // Test that the route binding works correctly
    $boundArtist = app('router')->getBindingCallback('artist')('mike-traditional');

    expect($boundArtist)->toBeInstanceOf(Artist::class);
    expect($boundArtist->username)->toBe('mike-traditional');
    expect($boundArtist->id)->toBe($artist->id);
});

it('maintains unique usernames', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $organization = Organization::factory()->create();

    // Create first artist with username
    Artist::factory()->create([
        'user_id' => $user1->id,
        'organization_id' => $organization->id,
        'username' => 'unique-username',
    ]);

    // Attempt to create second artist with same username should fail
    expect(function () use ($user2, $organization) {
        Artist::factory()->create([
            'user_id' => $user2->id,
            'organization_id' => $organization->id,
            'username' => 'unique-username',
        ]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});
