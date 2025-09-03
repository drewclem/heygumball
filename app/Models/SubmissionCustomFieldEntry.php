<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmissionCustomFieldEntry extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'submission_id',
        'custom_field_id',
        'value',
        'file_path',
        'file_original_name',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    // Get formatted value based on field type
    public function getFormattedValueAttribute()
    {
        $field = $this->customField;

        switch ($field->field_type) {
            case 'checkbox':
                return $this->value ? 'Yes' : 'No';
            case 'select':
            case 'radio':
                // Find the option label for this value
                $option = $field->options()->where('value', $this->value)->first();

                return $option ? $option->label : $this->value;
            case 'file':
                return $this->file_original_name ?? $this->value;
            default:
                return $this->value;
        }
    }

    public function hasFile(): bool
    {
        return ! empty($this->file_path);
    }

    public function getFileUrl()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function getFileSize()
    {
        if (! $this->file_path) {
            return null;
        }

        $fullPath = storage_path('app/public/' . $this->file_path);

        return file_exists($fullPath) ? filesize($fullPath) : null;
    }

    public function getFileSizeFormatted()
    {
        $size = $this->getFileSize();
        if (! $size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }
}
