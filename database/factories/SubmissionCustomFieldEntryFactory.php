<?php

namespace Database\Factories;

use App\Models\CustomField;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubmissionCustomFieldEntry>
 */
class SubmissionCustomFieldEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $submission = SubmissionFactory::new()->create();
        $customField = CustomFieldFactory::new()->create();

        return [
            'submission_id' => $submission->id,
            'custom_field_id' => $customField->id,
            'value' => $this->generateValueForFieldType($customField->field_type),
            'file_path' => null,
            'file_original_name' => null,
        ];
    }

    private function generateValueForFieldType($fieldType): string
    {
        $fieldTypeValue = is_string($fieldType) ? $fieldType : $fieldType->value;

        return match ($fieldTypeValue) {
            'text' => fake()->words(3, true),
            'textarea' => fake()->paragraph(),
            'select', 'radio' => fake()->randomElement(['small', 'medium', 'large', 'under-100', '100-300', '300-500']),
            'checkbox' => fake()->boolean() ? '1' : '0',
            'file' => 'reference_photo.jpg',
            default => fake()->sentence(),
        };
    }

    /**
     * Create a text field entry.
     */
    public function text(): static
    {
        return $this->state(fn(array $attributes) => [
            'value' => fake()->words(3, true),
            'file_path' => null,
            'file_original_name' => null,
        ]);
    }

    /**
     * Create a textarea field entry.
     */
    public function textarea(): static
    {
        return $this->state(fn(array $attributes) => [
            'value' => fake()->paragraph(),
            'file_path' => null,
            'file_original_name' => null,
        ]);
    }

    /**
     * Create a select/radio field entry.
     */
    public function select(): static
    {
        $options = [
            'Small (1-3 inches)' => 'small',
            'Medium (3-6 inches)' => 'medium',
            'Large (6-10 inches)' => 'large',
            'Extra Large (10+ inches)' => 'extra-large',
            'Under $100' => 'under-100',
            '$100 - $300' => '100-300',
            '$300 - $500' => '300-500',
            '$500 - $1000' => '500-1000',
            '$1000+' => '1000-plus',
            'Yes' => 'yes',
            'No' => 'no',
        ];

        $option = fake()->randomElement(array_keys($options));

        return $this->state(fn(array $attributes) => [
            'value' => $options[$option],
            'file_path' => null,
            'file_original_name' => null,
        ]);
    }

    /**
     * Create a checkbox field entry.
     */
    public function checkbox(): static
    {
        return $this->state(fn(array $attributes) => [
            'value' => fake()->boolean() ? '1' : '0',
            'file_path' => null,
            'file_original_name' => null,
        ]);
    }

    /**
     * Create a file field entry.
     */
    public function file(): static
    {
        $fileNames = [
            'dragon_reference.jpg',
            'tattoo_idea.png',
            'placement_photo.jpg',
            'style_inspiration.jpg',
            'color_palette.jpg',
            'size_reference.jpg',
            'design_sketch.jpg',
            'artist_inspiration.jpg',
        ];

        $fileName = fake()->randomElement($fileNames);
        $filePath = 'submissions/' . fake()->uuid() . '/' . $fileName;

        return $this->state(fn(array $attributes) => [
            'value' => $fileName,
            'file_path' => $filePath,
            'file_original_name' => $fileName,
        ]);
    }

    /**
     * Create an entry for a specific submission and custom field.
     */
    public function forSubmissionAndField(Submission $submission, CustomField $customField): static
    {
        return $this->state(fn(array $attributes) => [
            'submission_id' => $submission->id,
            'custom_field_id' => $customField->id,
            'value' => $this->generateValueForFieldType($customField->field_type),
            'file_path' => $customField->field_type->value === 'file' ? 'submissions/' . $submission->id . '/reference.jpg' : null,
            'file_original_name' => $customField->field_type->value === 'file' ? 'reference.jpg' : null,
        ]);
    }

    /**
     * Create realistic tattoo description entry.
     */
    public function tattooDescription(): static
    {
        $descriptions = [
            'Traditional Japanese dragon sleeve on left arm, full color with red and gold accents',
            'Minimalist geometric pattern on right forearm, black ink only, clean lines',
            'Realistic portrait of my grandmother on upper back, black and gray shading',
            'Watercolor floral piece on right shoulder, soft pastel colors, organic feel',
            'Blackwork tribal design on left calf, bold geometric patterns',
            'Script text on inner wrist, "Family First" in elegant font',
            'Animal design - wolf head on right shoulder, realistic style with detailed fur',
            'Abstract line work on left ribcage, flowing curves and geometric shapes',
            'Traditional rose on right ankle, classic American style with red and green',
            'Mandala design on upper back, symmetrical pattern with fine line work',
        ];

        return $this->state(fn(array $attributes) => [
            'value' => fake()->randomElement($descriptions),
            'file_path' => null,
            'file_original_name' => null,
        ]);
    }

    /**
     * Create realistic placement entry.
     */
    public function placement(): static
    {
        $placements = [
            'Left forearm',
            'Right shoulder',
            'Upper back',
            'Left calf',
            'Right ankle',
            'Inner wrist',
            'Left ribcage',
            'Right thigh',
            'Left bicep',
            'Lower back',
        ];

        return $this->state(fn(array $attributes) => [
            'value' => fake()->randomElement($placements),
            'file_path' => null,
            'file_original_name' => null,
        ]);
    }
}
