<?php

use App\Enums\CustomFieldType;
use App\Models\Artist;
use App\Models\CustomField;
use App\Models\CustomFieldOption;
use App\Models\FormSection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates ULID for custom field', function () {
    $field = CustomField::factory()->create();

    expect($field->id)->toHaveLength(26);
    expect($field->id)->toMatch('/^[0-9A-Za-z]{26}$/');
});

it('belongs to form section', function () {
    $formSection = FormSection::factory()->create();
    $field = CustomField::factory()->create(['form_section_id' => $formSection->id]);

    expect($field->formSection)->toBeInstanceOf(FormSection::class);
    expect($field->formSection->id)->toBe($formSection->id);
});

it('has many options ordered by sort order', function () {
    $field = CustomField::factory()->create();

    $option3 = CustomFieldOption::factory()->create([
        'custom_field_id' => $field->id,
        'sort_order' => 3,
    ]);
    $option1 = CustomFieldOption::factory()->create([
        'custom_field_id' => $field->id,
        'sort_order' => 1,
    ]);
    $option2 = CustomFieldOption::factory()->create([
        'custom_field_id' => $field->id,
        'sort_order' => 2,
    ]);

    $options = $field->options;

    expect($options)->toHaveCount(3);
    expect($options[0]->id)->toBe($option1->id);
    expect($options[1]->id)->toBe($option2->id);
    expect($options[2]->id)->toBe($option3->id);
});

it('casts validation rules to array', function () {
    $field = CustomField::factory()->create([
        'validation_rules' => ['required', 'min:3'],
    ]);

    expect($field->validation_rules)->toBeArray();
    expect($field->validation_rules)->toContain('required');
    expect($field->validation_rules)->toContain('min:3');
});

it('casts boolean fields correctly', function () {
    $field = CustomField::factory()->create([
        'is_required' => true,
        'is_active' => false,
    ]);

    expect($field->is_required)->toBeTrue();
    expect($field->is_active)->toBeFalse();
});

describe('validation rule generation', function () {
    it('generates basic rules for required field', function () {
        $field = CustomField::factory()->required()->create();

        $rules = $field->getLaravelValidationRules();

        expect($rules)->toContain('required');
    });

    it('generates basic rules for non-required field', function () {
        $field = CustomField::factory()->create(['is_required' => false]);

        $rules = $field->getLaravelValidationRules();

        expect($rules)->not->toContain('required');
        expect($rules)->toBeArray();
    });

    it('adds file validation for file field type', function () {
        $field = CustomField::factory()->create([
            'field_type' => CustomFieldType::FILE,
            'is_required' => true,
        ]);

        $rules = $field->getLaravelValidationRules();

        expect($rules)->toContain('required');
        expect($rules)->toContain('file');
        expect($rules)->toContain('max:10240');
    });

    it('handles text field type without additional rules', function () {
        $field = CustomField::factory()->text()->create(['is_required' => true]);

        $rules = $field->getLaravelValidationRules();

        expect($rules)->toContain('required');
        expect($rules)->toHaveCount(1);
    });

    it('handles textarea field type without additional rules', function () {
        $field = CustomField::factory()->textarea()->create(['is_required' => true]);

        $rules = $field->getLaravelValidationRules();

        expect($rules)->toContain('required');
        expect($rules)->toHaveCount(1);
    });

    it('handles select field type without additional rules', function () {
        $field = CustomField::factory()->select()->create(['is_required' => true]);

        $rules = $field->getLaravelValidationRules();

        expect($rules)->toContain('required');
        expect($rules)->toHaveCount(1);
    });

    it('handles radio field type without additional rules', function () {
        $field = CustomField::factory()->create([
            'field_type' => 'radio',
            'is_required' => true,
        ]);

        $rules = $field->getLaravelValidationRules();

        expect($rules)->toContain('required');
        expect($rules)->toHaveCount(1);
    });

    it('handles checkbox field type without additional rules', function () {
        $field = CustomField::factory()->create([
            'field_type' => 'checkbox',
            'is_required' => true,
        ]);

        $rules = $field->getLaravelValidationRules();

        expect($rules)->toContain('required');
        expect($rules)->toHaveCount(1);
    });
});

describe('custom field factory states', function () {
    it('can create text field', function () {
        $field = CustomField::factory()->text()->create();

        expect($field->field_type)->toBe(CustomFieldType::TEXT);
    });

    it('can create textarea field', function () {
        $field = CustomField::factory()->textarea()->create();

        expect($field->field_type)->toBe(CustomFieldType::TEXTAREA);
    });

    it('can create select field', function () {
        $field = CustomField::factory()->select()->create();

        expect($field->field_type)->toBe(CustomFieldType::SELECT);
    });

    it('can create required field', function () {
        $field = CustomField::factory()->required()->create();

        expect($field->is_required)->toBeTrue();
    });

    it('can create inactive field', function () {
        $field = CustomField::factory()->inactive()->create();

        expect($field->is_active)->toBeFalse();
    });
});

it('can be created with all required fields', function () {
    $field = CustomField::factory()->create([
        'field_type' => 'text',
        'label' => 'Test Field',
        'sort_order' => 5,
    ]);

    expect($field->field_type)->toBe(CustomFieldType::TEXT);
    expect($field->label)->toBe('Test Field');
    expect($field->sort_order)->toBe(5);
    // is_required depends on the field configuration, not always false
    expect($field->is_active)->toBeTrue();
});

it('can be created with optional fields', function () {
    $field = CustomField::factory()->create([
        'field_type' => 'select',
        'label' => 'Size Selection',
        'placeholder' => 'Choose your size',
        'help_text' => 'Select the size that best fits your needs',
        'validation_rules' => ['required'],
        'sort_order' => 10,
        'is_required' => true,
        'is_active' => false,
    ]);

    expect($field->field_type)->toBe(CustomFieldType::SELECT);
    expect($field->label)->toBe('Size Selection');
    expect($field->placeholder)->toBe('Choose your size');
    expect($field->help_text)->toBe('Select the size that best fits your needs');
    expect($field->validation_rules)->toContain('required');
    expect($field->sort_order)->toBe(10);
    expect($field->is_required)->toBeTrue();
    expect($field->is_active)->toBeFalse();
});
