<?php

namespace Database\Factories;

use App\Models\CustomField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomFieldOption>
 */
class CustomFieldOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'custom_field_id' => fn() => CustomFieldFactory::new()->create(),
            'label' => fake()->words(2, true),
            'value' => fake()->slug(),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Create size options for tattoo size field.
     */
    public function sizeOptions(): static
    {
        $sizes = [
            'Small (1-3 inches)',
            'Medium (3-6 inches)',
            'Large (6-10 inches)',
            'Extra Large (10+ inches)',
        ];

        return $this->state(fn(array $attributes) => [
            'label' => fake()->randomElement($sizes),
            'value' => strtolower(str_replace(['(', ')', ' '], ['', '', '-'], fake()->randomElement($sizes))),
        ]);
    }

    /**
     * Create budget range options.
     */
    public function budgetOptions(): static
    {
        $budgets = [
            'Under $100',
            '$100 - $300',
            '$300 - $500',
            '$500 - $1000',
            '$1000+',
        ];

        return $this->state(fn(array $attributes) => [
            'label' => fake()->randomElement($budgets),
            'value' => strtolower(str_replace(['$', ' ', '+'], ['', '-', 'plus'], fake()->randomElement($budgets))),
        ]);
    }

    /**
     * Create timeline options.
     */
    public function timelineOptions(): static
    {
        $timelines = [
            'ASAP',
            'Within 1 month',
            'Within 3 months',
            'Within 6 months',
            'No rush',
        ];

        return $this->state(fn(array $attributes) => [
            'label' => fake()->randomElement($timelines),
            'value' => strtolower(str_replace(' ', '-', fake()->randomElement($timelines))),
        ]);
    }

    /**
     * Create yes/no options for radio fields.
     */
    public function yesNoOptions(): static
    {
        $options = ['Yes', 'No'];

        return $this->state(fn(array $attributes) => [
            'label' => fake()->randomElement($options),
            'value' => strtolower(fake()->randomElement($options)),
        ]);
    }
}
