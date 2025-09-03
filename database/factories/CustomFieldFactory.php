<?php

namespace Database\Factories;

use App\Models\Artist;
use App\Models\FormSection;
use App\Models\ArtistForm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomField>
 */
class CustomFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fieldTypes = ['text', 'textarea', 'select', 'radio', 'checkbox', 'file'];
        $fieldType = fake()->randomElement($fieldTypes);

        $commonFields = [
            'Tattoo Description' => [
                'type' => 'textarea',
                'placeholder' => 'Describe your tattoo idea in detail...',
                'help_text' => 'Include style preferences, colors, and any specific elements you want.',
                'required' => true,
            ],
            'Placement' => [
                'type' => 'text',
                'placeholder' => 'Where on your body?',
                'help_text' => 'e.g., left forearm, right shoulder, back, etc.',
                'required' => true,
            ],
            'Size' => [
                'type' => 'select',
                'placeholder' => 'Select size range',
                'help_text' => 'Choose the approximate size of your tattoo.',
                'required' => true,
            ],
            'Budget Range' => [
                'type' => 'select',
                'placeholder' => 'Select your budget',
                'help_text' => 'This helps us understand your expectations.',
                'required' => false,
            ],
            'Reference Photos' => [
                'type' => 'file',
                'placeholder' => 'Upload reference images',
                'help_text' => 'Upload any reference photos or inspiration images (max 10MB).',
                'required' => false,
            ],
            'First Tattoo?' => [
                'type' => 'radio',
                'placeholder' => 'Is this your first tattoo?',
                'help_text' => 'This helps us provide appropriate guidance.',
                'required' => false,
            ],
            'Timeline' => [
                'type' => 'select',
                'placeholder' => 'When do you want this done?',
                'help_text' => 'Select your preferred timeline.',
                'required' => false,
            ],
            'Allergies' => [
                'type' => 'textarea',
                'placeholder' => 'Any allergies or medical conditions?',
                'help_text' => 'Important for your safety during the tattoo process.',
                'required' => false,
            ],
        ];

        $fieldName = fake()->randomElement(array_keys($commonFields));
        $fieldConfig = $commonFields[$fieldName];

        return [
            'form_section_id' => fn() => FormSectionFactory::new()->create(),
            'field_type' => $fieldConfig['type'],
            'label' => $fieldName,
            'placeholder' => $fieldConfig['placeholder'],
            'help_text' => $fieldConfig['help_text'],
            'validation_rules' => null,
            'sort_order' => fake()->numberBetween(1, 100),
            'is_required' => $fieldConfig['required'],
            'is_active' => true,
            'is_built_in' => false,
        ];
    }

    /**
     * Create a text field.
     */
    public function text(): static
    {
        return $this->state(fn(array $attributes) => [
            'field_type' => 'text',
            'label' => fake()->words(2, true),
            'placeholder' => fake()->sentence(),
        ]);
    }

    /**
     * Create a textarea field.
     */
    public function textarea(): static
    {
        return $this->state(fn(array $attributes) => [
            'field_type' => 'textarea',
            'label' => fake()->words(3, true),
            'placeholder' => fake()->sentence(),
        ]);
    }

    /**
     * Create a select field.
     */
    public function select(): static
    {
        return $this->state(fn(array $attributes) => [
            'field_type' => 'select',
            'label' => fake()->words(2, true),
            'placeholder' => fake()->sentence(),
        ]);
    }

    /**
     * Create a required field.
     */
    public function required(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_required' => true,
        ]);
    }

    /**
     * Create an inactive field.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a custom field for a specific form section.
     */
    public function forFormSection(FormSection $formSection): static
    {
        return $this->state(fn(array $attributes) => [
            'form_section_id' => $formSection->id,
        ]);
    }

    /**
     * Create a built-in field.
     */
    public function builtIn(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_built_in' => true,
        ]);
    }
}
