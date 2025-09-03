<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;

class Organization extends Model
{
    use Billable, HasFactory;

    protected $fillable = [
        'name',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    public function artists(): HasMany
    {
        return $this->hasMany(Artist::class);
    }

    public function activeArtists(): HasMany
    {
        return $this->artists()->where('status', 'active');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    // Subscription plans configuration
    public function getAllowedArtistCount(): int
    {
        $subscription = $this->subscription('default');

        if (! $subscription || ! $subscription->active()) {
            return $this->isOwnerOnly() ? 1 : 0; // Free tier allows 1 artist
        }

        // Return limits based on subscription price
        return match ($subscription->stripe_price) {
            'price_solo_monthly' => 1,
            'price_studio_basic_monthly' => 5,
            'price_studio_pro_monthly' => 15,
            'price_enterprise_monthly' => 999, // "unlimited"
            default => 1
        };
    }

    public function canAddArtist(): bool
    {
        return $this->activeArtists()->count() < $this->getAllowedArtistCount();
    }

    public function getMonthlySubmissionLimit(): int
    {
        $subscription = $this->subscription('default');

        if (! $subscription || ! $subscription->active()) {
            return 50; // Free tier limit
        }

        return match ($subscription->stripe_price) {
            'price_solo_monthly' => 200,
            'price_studio_basic_monthly' => 1000,
            'price_studio_pro_monthly' => 5000,
            'price_enterprise_monthly' => 999999, // "unlimited"
            default => 50
        };
    }

    public function getCurrentMonthSubmissionCount(): int
    {
        return $this->artists()
            ->join('submissions', 'artists.id', '=', 'submissions.artist_id')
            ->whereBetween('submissions.submitted_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->count();
    }

    public function canAcceptSubmission(): bool
    {
        return $this->getCurrentMonthSubmissionCount() < $this->getMonthlySubmissionLimit();
    }

    private function isOwnerOnly(): bool
    {
        return $this->artists()->count() <= 1;
    }
}
