<?php

use App\Models\Tag;
use App\Models\Artist;
use App\Models\Client;
use App\Models\Submission;
use Database\Factories\TagFactory;
use App\Enums\SubmissionStatusType;
use Database\Factories\ArtistFactory;
use Database\Factories\ClientFactory;
use Database\Factories\SubmissionFactory;
use App\Models\SubmissionCustomFieldEntry;
use Database\Factories\CustomFieldFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Factories\SubmissionCustomFieldEntryFactory;

uses(RefreshDatabase::class);

it('generates ULID for submission', function () {
    $submission = SubmissionFactory::new()->create();

    expect($submission->id)->toHaveLength(26);
    expect($submission->id)->toMatch('/^[0-9A-Za-z]{26}$/');
});

it('belongs to artist and client', function () {
    $artist = ArtistFactory::new()->create();
    $client = ClientFactory::new()->create(['artist_id' => $artist->id]);
    $submission = SubmissionFactory::new()->create([
        'artist_id' => $artist->id,
        'client_id' => $client->id,
    ]);

    expect($submission->artist)->toBeInstanceOf(Artist::class);
    expect($submission->artist->id)->toBe($artist->id);
    expect($submission->client)->toBeInstanceOf(Client::class);
    expect($submission->client->id)->toBe($client->id);
});

it('has many custom field entries', function () {
    $submission = SubmissionFactory::new()->create();
    $entries = SubmissionCustomFieldEntryFactory::new()->count(3)->create(['submission_id' => $submission->id]);

    expect($submission->customFieldEntries)->toHaveCount(3);
    expect($submission->customFieldEntries->first())->toBeInstanceOf(SubmissionCustomFieldEntry::class);
});

it('can be tagged polymorphically', function () {
    $submission = SubmissionFactory::new()->create();
    $tag = TagFactory::new()->create(['artist_id' => $submission->artist_id]);

    $submission->tags()->attach($tag->id);

    expect($submission->tags)->toHaveCount(1);
    expect($submission->tags->first())->toBeInstanceOf(Tag::class);
    expect($submission->tags->first()->id)->toBe($tag->id);
});

it('snapshots client data at submission time', function () {
    $artist = ArtistFactory::new()->create();
    $client = ClientFactory::new()->create([
        'artist_id' => $artist->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '555-123-4567',
    ]);

    $submission = SubmissionFactory::new()->create([
        'artist_id' => $artist->id,
        'client_id' => $client->id,
        'client_name' => $client->name,
        'client_email' => $client->email,
        'client_phone' => $client->phone,
    ]);

    // Update client data after submission
    $client->update([
        'name' => 'John Smith',
        'email' => 'johnsmith@example.com',
        'phone' => '555-987-6543',
    ]);

    // Submission should still have original data
    expect($submission->fresh()->client_name)->toBe('John Doe');
    expect($submission->fresh()->client_email)->toBe('john@example.com');
    expect($submission->fresh()->client_phone)->toBe('555-123-4567');
});

it('can get response for specific custom field', function () {
    $submission = SubmissionFactory::new()->create();
    // Create a custom field that belongs to the same artist form
    $customField = CustomFieldFactory::new()->create([
        'form_section_id' => $submission->artistForm->sections()->first()->id
    ]);

    SubmissionCustomFieldEntryFactory::new()->create([
        'submission_id' => $submission->id,
        'custom_field_id' => $customField->id,
        'value' => 'Dragon sleeve design',
    ]);

    $response = $submission->getResponseForField($customField->id);
    expect($response)->toBe('Dragon sleeve design');
});

it('can get file information for file fields', function () {
    $submission = SubmissionFactory::new()->create();
    // Create a custom field that belongs to the same artist form
    $customField = CustomFieldFactory::new()->create([
        'form_section_id' => $submission->artistForm->sections()->first()->id
    ]);

    SubmissionCustomFieldEntryFactory::new()->create([
        'submission_id' => $submission->id,
        'custom_field_id' => $customField->id,
        'value' => 'reference.jpg',
        'file_path' => 'submissions/123/reference.jpg',
        'file_original_name' => 'dragon_reference.jpg',
    ]);

    $fileInfo = $submission->getFileForField($customField->id);

    expect($fileInfo)->toBeArray();
    expect($fileInfo['path'])->toBe('submissions/123/reference.jpg');
    expect($fileInfo['original_name'])->toBe('dragon_reference.jpg');
    expect($fileInfo['url'])->toContain('storage/submissions/123/reference.jpg');
});

it('returns null for non-existent field response', function () {
    $submission = SubmissionFactory::new()->create();
    $nonExistentFieldId = '01k46n5m100ygddwzse7fm9g90';

    $response = $submission->getResponseForField($nonExistentFieldId);
    expect($response)->toBeNull();
});

it('returns null for file field without file', function () {
    $submission = SubmissionFactory::new()->create();
    // Create a custom field that belongs to the same artist form
    $customField = CustomFieldFactory::new()->create([
        'form_section_id' => $submission->artistForm->sections()->first()->id
    ]);

    SubmissionCustomFieldEntryFactory::new()->create([
        'submission_id' => $submission->id,
        'custom_field_id' => $customField->id,
        'value' => 'No file uploaded',
        'file_path' => null,
        'file_original_name' => null,
    ]);

    $fileInfo = $submission->getFileForField($customField->id);
    expect($fileInfo)->toBeNull();
});

describe('status management', function () {
    it('can approve submission', function () {
        $submission = SubmissionFactory::new()->create(['status' => SubmissionStatusType::PENDING]);

        $submission->approve();

        expect($submission->fresh()->status)->toBe(SubmissionStatusType::APPROVED);
        expect($submission->fresh()->reviewed_at)->not->toBeNull();
        expect($submission->fresh()->rejection_reason)->toBeNull();
    });

    it('can reject submission with reason', function () {
        $submission = SubmissionFactory::new()->create(['status' => SubmissionStatusType::PENDING]);
        $reason = 'Design is too complex for requested size';

        $submission->reject($reason);

        expect($submission->fresh()->status)->toBe(SubmissionStatusType::REJECTED);
        expect($submission->fresh()->reviewed_at)->not->toBeNull();
        expect($submission->fresh()->rejection_reason)->toBe($reason);
    });

    it('can reject submission without reason', function () {
        $submission = SubmissionFactory::new()->create(['status' => SubmissionStatusType::PENDING]);

        $submission->reject();

        expect($submission->fresh()->status)->toBe(SubmissionStatusType::REJECTED);
        expect($submission->fresh()->reviewed_at)->not->toBeNull();
        expect($submission->fresh()->rejection_reason)->toBeNull();
    });
});

describe('status checking methods', function () {
    it('correctly identifies pending status', function () {
        $submission = SubmissionFactory::new()->pending()->create();
        expect($submission->isPending())->toBeTrue();
    });

    it('correctly identifies approved status', function () {
        $submission = SubmissionFactory::new()->approved()->create();
        expect($submission->isApproved())->toBeTrue();
    });

    it('correctly identifies rejected status', function () {
        $submission = SubmissionFactory::new()->rejected()->create();
        expect($submission->isRejected())->toBeTrue();
    });

    it('correctly identifies under review status', function () {
        $submission = SubmissionFactory::new()->create(['status' => SubmissionStatusType::UNDER_REVIEW]);
        expect($submission->isUnderReview())->toBeTrue();
    });

    it('correctly identifies scheduled status', function () {
        $submission = SubmissionFactory::new()->create(['status' => SubmissionStatusType::SCHEDULED]);
        expect($submission->isScheduled())->toBeTrue();
    });

    it('correctly identifies completed status', function () {
        $submission = SubmissionFactory::new()->create(['status' => SubmissionStatusType::COMPLETED]);
        expect($submission->isCompleted())->toBeTrue();
    });

    it('correctly identifies cancelled status', function () {
        $submission = SubmissionFactory::new()->create(['status' => SubmissionStatusType::CANCELLED]);
        expect($submission->isCancelled())->toBeTrue();
    });
});

describe('query scopes', function () {
    it('can filter by pending status', function () {
        SubmissionFactory::new()->pending()->count(3)->create();
        SubmissionFactory::new()->approved()->count(2)->create();

        $pendingSubmissions = Submission::pending()->get();

        expect($pendingSubmissions)->toHaveCount(3);
        expect($pendingSubmissions->every(fn($s) => $s->isPending()))->toBeTrue();
    });

    it('can filter by approved status', function () {
        SubmissionFactory::new()->pending()->count(2)->create();
        SubmissionFactory::new()->approved()->count(4)->create();

        $approvedSubmissions = Submission::approved()->get();

        expect($approvedSubmissions)->toHaveCount(4);
        expect($approvedSubmissions->every(fn($s) => $s->isApproved()))->toBeTrue();
    });

    it('can filter by rejected status', function () {
        SubmissionFactory::new()->pending()->count(2)->create();
        SubmissionFactory::new()->rejected()->count(3)->create();

        $rejectedSubmissions = Submission::rejected()->get();

        expect($rejectedSubmissions)->toHaveCount(3);
        expect($rejectedSubmissions->every(fn($s) => $s->isRejected()))->toBeTrue();
    });

    it('can filter by under review status', function () {
        SubmissionFactory::new()->pending()->count(2)->create();
        SubmissionFactory::new()->create(['status' => SubmissionStatusType::UNDER_REVIEW]);

        $underReviewSubmissions = Submission::underReview()->get();

        expect($underReviewSubmissions)->toHaveCount(1);
        expect($underReviewSubmissions->first()->isUnderReview())->toBeTrue();
    });
});

describe('factory states', function () {
    it('can create pending submission', function () {
        $submission = SubmissionFactory::new()->pending()->create();

        expect($submission->status)->toBe(SubmissionStatusType::PENDING);
        expect($submission->reviewed_at)->toBeNull();
        expect($submission->rejection_reason)->toBeNull();
    });

    it('can create approved submission', function () {
        $submission = SubmissionFactory::new()->approved()->create();

        expect($submission->status)->toBe(SubmissionStatusType::APPROVED);
        expect($submission->reviewed_at)->not->toBeNull();
        expect($submission->rejection_reason)->toBeNull();
    });

    it('can create rejected submission', function () {
        $submission = SubmissionFactory::new()->rejected()->create();

        expect($submission->status)->toBe(SubmissionStatusType::REJECTED);
        expect($submission->reviewed_at)->not->toBeNull();
        expect($submission->rejection_reason)->not->toBeNull();
    });

    it('can create completed submission', function () {
        $submission = SubmissionFactory::new()->completed()->create();

        expect($submission->status)->toBe(SubmissionStatusType::COMPLETED);
        expect($submission->reviewed_at)->not->toBeNull();
        expect($submission->rejection_reason)->toBeNull();
    });

    it('can create submission for specific artist and client', function () {
        $artist = ArtistFactory::new()->create();
        $client = ClientFactory::new()->create(['artist_id' => $artist->id]);

        $submission = SubmissionFactory::new()->forArtistAndClient($artist, $client)->create();

        expect($submission->artist_id)->toBe($artist->id);
        expect($submission->client_id)->toBe($client->id);
        expect($submission->client_name)->toBe($client->name);
        expect($submission->client_email)->toBe($client->email);
        expect($submission->client_phone)->toBe($client->phone);
    });
});
