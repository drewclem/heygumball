<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormSection extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'artist_form_id',
        'title',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function artistForm(): BelongsTo
    {
        return $this->belongsTo(ArtistForm::class);
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(CustomField::class)->orderBy('sort_order');
    }

    public function activeFields(): HasMany
    {
        return $this->customFields()->where('is_active', true);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function getIconAttribute($value)
    {
        return $value ?: 'document-text'; // Default icon
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
