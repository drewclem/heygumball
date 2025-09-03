<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'artist_id',
        'name',
        'color',
        'description',
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    // Polymorphic relationships
    public function clients(): MorphToMany
    {
        return $this->morphedByMany(Client::class, 'taggable');
    }

    public function submissions(): MorphToMany
    {
        return $this->morphedByMany(Submission::class, 'taggable');
    }

    // Helper methods
    public function getColorAttribute($value)
    {
        return $value ?: '#6b7280'; // Default gray color
    }

    public function getUsageCountAttribute()
    {
        return $this->clients()->count() + $this->submissions()->count();
    }

    public function scopeForArtist($query, $artistId)
    {
        return $query->where('artist_id', $artistId);
    }

    public function scopeWithUsage($query)
    {
        return $query->withCount(['clients', 'submissions']);
    }

    public function scopePopular($query, $artistId = null)
    {
        $query = $query->withUsage();

        if ($artistId) {
            $query->forArtist($artistId);
        }

        return $query->orderByRaw('(clients_count + submissions_count) DESC');
    }
}
