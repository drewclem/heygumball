<?php

namespace App\Models;

use App\Enums\CustomFieldType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'form_section_id',
        'field_key',
        'field_type',
        'label',
        'placeholder',
        'help_text',
        'validation_rules',
        'sort_order',
        'is_required',
        'is_built_in',
        'is_active',
    ];

    protected $casts = [
        'validation_rules' => 'array',
        'is_required' => 'boolean',
        'is_built_in' => 'boolean',
        'is_active' => 'boolean',
        'field_type' => CustomFieldType::class,
    ];

    public function formSection(): BelongsTo
    {
        return $this->belongsTo(FormSection::class);
    }

    public function artist()
    {
        return $this->formSection->artistForm->artist;
    }

    public function options(): HasMany
    {
        return $this->hasMany(CustomFieldOption::class)->orderBy('sort_order');
    }

    public function getLaravelValidationRules(): array
    {
        $rules = [];

        if ($this->is_required) {
            $rules[] = 'required';
        }

        switch ($this->field_type) {
            case CustomFieldType::FILE:
                $rules[] = 'file';
                $rules[] = 'max:10240'; // 10MB
                break;
        }

        return $rules;
    }
}
