<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtistForm extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'artist_id',
        'title',
        'welcome_message',
        'submission_success_message',
        'is_active',
        'auto_close_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_close_at' => 'datetime',
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(FormSection::class)->orderBy('sort_order');
    }

    public function activeSections(): HasMany
    {
        return $this->sections()->where('is_active', true);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function allFields()
    {
        $sectionFields = $this->sections()
            ->with('customFields')
            ->get()
            ->pluck('customFields')
            ->flatten();

        return $sectionFields
            ->concat($this->getBuiltInContactFields())
            ->concat($this->getBuiltInConsentFields())
            ->sortBy('sort_order');
    }

    public function getBuiltInContactFields()
    {
        return collect([
            (object) [
                'id' => 'built_in_first_name',
                'field_key' => 'first_name',
                'field_type' => 'text',
                'label' => 'First Name',
                'is_required' => true,
                'is_built_in' => true,
                'sort_order' => 0,
                'placeholder' => 'Enter your first name',
            ],
            (object) [
                'id' => 'built_in_last_name',
                'field_key' => 'last_name',
                'field_type' => 'text',
                'label' => 'Last Name',
                'is_required' => true,
                'is_built_in' => true,
                'sort_order' => 1,
                'placeholder' => 'Enter your last name',
            ],
            (object) [
                'id' => 'built_in_email',
                'field_key' => 'email',
                'field_type' => 'email',
                'label' => 'Email Address',
                'is_required' => true,
                'is_built_in' => true,
                'sort_order' => 2,
                'placeholder' => 'Enter your email address',
            ],
            (object) [
                'id' => 'built_in_phone_number',
                'field_key' => 'phone_number',
                'field_type' => 'phone',
                'label' => 'Phone Number',
                'is_required' => true,
                'is_built_in' => true,
                'sort_order' => 3,
                'placeholder' => 'Enter your phone number',
            ],
            (object) [
                'id' => 'built_in_age',
                'field_key' => 'age',
                'field_type' => 'number',
                'label' => 'Age',
                'is_required' => true,
                'is_built_in' => true,
                'sort_order' => 4,
                'placeholder' => 'Enter your age',
                'validation_rules' => ['min:18'],
            ],
        ]);
    }

    public function getBuiltInConsentFields()
    {
        return collect([
            (object) [
                'id' => 'built_in_age_confirmation',
                'field_key' => 'age_confirmation',
                'field_type' => 'checkbox',
                'label' => 'I confirm I am 18 years or older',
                'is_required' => true,
                'is_built_in' => true,
                'sort_order' => 1000,
            ],
            (object) [
                'id' => 'built_in_terms_agreement',
                'field_key' => 'terms_agreement',
                'field_type' => 'checkbox',
                'label' => 'I agree to Heygumball\'s Terms & Conditions',
                'is_required' => true,
                'is_built_in' => true,
                'sort_order' => 1001,
            ],
        ]);
    }

    public function getFieldByKey(string $fieldKey)
    {
        $builtInField = $this->getBuiltInContactFields()
            ->concat($this->getBuiltInConsentFields())
            ->firstWhere('field_key', $fieldKey);

        if ($builtInField) {
            return $builtInField;
        }

        return $this->sections()
            ->with('customFields')
            ->get()
            ->pluck('customFields')
            ->flatten()
            ->firstWhere('field_key', $fieldKey);
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
}
