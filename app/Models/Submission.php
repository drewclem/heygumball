<?php

namespace App\Models;

use App\Enums\SubmissionStatusType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'artist_id',
        'client_id',
        'artist_form_id',
        'status',
        'artist_notes',
        'rejection_reason',
        'estimated_duration',
        'estimated_price',
        'submitted_at',
        'reviewed_at',
        'client_name',
        'client_email',
        'client_phone',
    ];

    protected $casts = [
        'status' => SubmissionStatusType::class,
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'estimated_duration' => 'integer',
        'estimated_price' => 'decimal:2',
    ];

    // Relationships
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function artistForm(): BelongsTo
    {
        return $this->belongsTo(ArtistForm::class);
    }

    public function customFieldEntries(): HasMany
    {
        return $this->hasMany(SubmissionCustomFieldEntry::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    // Helper methods
    public function getResponseForField($customFieldId)
    {
        return $this->customFieldEntries()
            ->where('custom_field_id', $customFieldId)
            ->first()?->value;
    }

    public function getFileForField($customFieldId)
    {
        $entry = $this->customFieldEntries()
            ->where('custom_field_id', $customFieldId)
            ->whereNotNull('file_path')
            ->first();

        return $entry ? [
            'path' => $entry->file_path,
            'original_name' => $entry->file_original_name,
            'url' => asset('storage/' . $entry->file_path),
        ] : null;
    }

    public function approve()
    {
        $this->update([
            'status' => SubmissionStatusType::APPROVED,
            'reviewed_at' => now(),
        ]);
    }

    public function reject($reason = null)
    {
        $this->update([
            'status' => SubmissionStatusType::REJECTED,
            'rejection_reason' => $reason,
            'reviewed_at' => now(),
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === SubmissionStatusType::PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === SubmissionStatusType::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === SubmissionStatusType::REJECTED;
    }

    public function isUnderReview(): bool
    {
        return $this->status === SubmissionStatusType::UNDER_REVIEW;
    }

    public function isScheduled(): bool
    {
        return $this->status === SubmissionStatusType::SCHEDULED;
    }

    public function isCompleted(): bool
    {
        return $this->status === SubmissionStatusType::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === SubmissionStatusType::CANCELLED;
    }

    public function scopePending($query)
    {
        return $query->where('status', SubmissionStatusType::PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', SubmissionStatusType::APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', SubmissionStatusType::REJECTED);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', SubmissionStatusType::UNDER_REVIEW);
    }
}
