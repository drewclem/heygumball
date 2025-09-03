<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\ArtistRoleType;
use App\Enums\ArtistStatusType;
use Illuminate\Support\Facades\DB;
use App\Services\BuiltInFieldService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artist extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'organization_id',
        'username',
        'display_name',
        'bio',
        'specialties',
        'hourly_rate',
        'role',
        'status',
        'joined_at',
        'exited_at',
    ];

    protected $casts = [
        'specialties' => 'array',
        'hourly_rate' => 'decimal:2',
        'joined_at' => 'datetime',
        'exited_at' => 'datetime',
        'role' => ArtistRoleType::class,
        'status' => ArtistStatusType::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function getAllCustomFields()
    {
        // Get all custom fields from all form sections
        if (!$this->form) {
            return collect();
        }

        return $this->form->sections()
            ->with('customFields')
            ->get()
            ->flatMap(function ($section) {
                return $section->customFields;
            })
            ->sortBy('sort_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function form()
    {
        return $this->hasOne(ArtistForm::class);
    }

    public function getOrCreateForm()
    {
        if (!$this->form) {
            $this->createDefaultForm();
            $this->refresh();
        }
        return $this->form;
    }

    private function createDefaultForm()
    {
        return DB::transaction(function () {
            $form = $this->form()->create([
                'title' => "{$this->display_name}'s Booking Form",
                'welcome_message' => "Thanks for your interest in getting tattooed! Please fill out this form with as much detail as possible.",
                'submission_success_message' => "Thank you! I'll review your submission and get back to you within 48 hours."
            ]);

            BuiltInFieldService::createDefaultForm($form);

            return $form;
        });
    }

    public function findOrCreateClient(string $email, array $clientData = []): Client
    {
        return $this->clients()->firstOrCreate(
            ['email' => $email],
            array_merge(['name' => $clientData['name'] ?? ''], $clientData)
        );
    }

    public function isBookingFormOpen(): bool
    {
        $form = $this->form;

        if (!$form || !$form->isActive()) {
            return false;
        }

        // Check if auto-close time has passed
        if ($form->auto_close_at && $form->auto_close_at->isPast()) {
            return false;
        }

        return true;
    }

    public function openBookingForm(): void
    {
        $form = $this->getOrCreateForm();
        $form->activate();
    }

    public function closeBookingForm(): void
    {
        $form = $this->getOrCreateForm();
        $form->deactivate();
    }

    public function closeBookingFormAt(Carbon $closeTime): void
    {
        $form = $this->getOrCreateForm();
        $form->update(['auto_close_at' => $closeTime]);
    }



    public function getBookingUrlAttribute(): string
    {
        return url('/' . $this->username);
    }

    public function pendingSubmissions()
    {
        return $this->submissions()->where('status', 'pending');
    }

    public function canAcceptSubmissions(): bool
    {
        return $this->isBookingFormOpen() && $this->organization->canAcceptSubmission();
    }
}
