<?php

namespace App\Services;

use App\Models\ArtistForm;
use App\Models\FormSection;
use App\Models\CustomField;
use App\Enums\CustomFieldType;

class BuiltInFieldService
{
    public const CONTACT_FIELDS = [
        'first_name' => [
            'type' => CustomFieldType::TEXT,
            'label' => 'First Name',
            'placeholder' => 'Enter your first name',
            'help_text' => 'Your legal first name',
            'required' => true,
        ],
        'last_name' => [
            'type' => CustomFieldType::TEXT,
            'label' => 'Last Name',
            'placeholder' => 'Enter your last name',
            'help_text' => 'Your legal last name',
            'required' => true,
        ],
        'email' => [
            'type' => CustomFieldType::EMAIL,
            'label' => 'Email Address',
            'placeholder' => 'your.email@example.com',
            'help_text' => 'We\'ll use this to contact you about your appointment',
            'required' => true,
        ],
        'phone_number' => [
            'type' => CustomFieldType::PHONE,
            'label' => 'Phone Number',
            'placeholder' => '(555) 123-4567',
            'help_text' => 'Best number to reach you',
            'required' => true,
        ],
        'age' => [
            'type' => CustomFieldType::NUMBER,
            'label' => 'Age',
            'placeholder' => '18',
            'help_text' => 'Must be 18 or older',
            'required' => true,
            'validation_rules' => ['min:18'],
        ],
    ];

    public const CONSENT_FIELDS = [
        'age_confirmation' => [
            'type' => CustomFieldType::CHECKBOX,
            'label' => 'Age Confirmation',
            'placeholder' => 'I confirm I am 18 years or older',
            'help_text' => 'You must be 18 or older to get a tattoo',
            'required' => true,
        ],
        'terms_agreement' => [
            'type' => CustomFieldType::CHECKBOX,
            'label' => 'Terms Agreement',
            'placeholder' => 'I agree to the terms and conditions',
            'help_text' => 'Please read and agree to our terms',
            'required' => true,
        ],
    ];

    public const DEFAULT_SECTIONS = [
        [
            'title' => 'Tattoo Details',
            'description' => 'Tell us about your tattoo idea',
            'icon' => 'pencil',
            'fields' => [
                'description' => [
                    'type' => CustomFieldType::TEXTAREA,
                    'label' => 'Tattoo Description',
                    'placeholder' => 'Describe your tattoo idea in detail...',
                    'help_text' => 'Include style preferences, colors, and any specific elements you want.',
                    'required' => true,
                ],
                'placement' => [
                    'type' => CustomFieldType::TEXT,
                    'label' => 'Placement',
                    'placeholder' => 'Where on your body?',
                    'help_text' => 'e.g., left forearm, right shoulder, back, etc.',
                    'required' => false,
                ],
                'size' => [
                    'type' => CustomFieldType::SELECT,
                    'label' => 'Size',
                    'placeholder' => 'Select size range',
                    'help_text' => 'Choose the approximate size of your tattoo.',
                    'required' => false,
                    'options' => [
                        'Small (1-3 inches)' => 'small',
                        'Medium (3-6 inches)' => 'medium',
                        'Large (6-10 inches)' => 'large',
                        'Extra Large (10+ inches)' => 'extra-large',
                    ],
                ],
                'style' => [
                    'type' => CustomFieldType::SELECT,
                    'label' => 'Style',
                    'placeholder' => 'Select your preferred style',
                    'help_text' => 'What artistic style are you looking for?',
                    'required' => false,
                    'options' => [
                        'Traditional' => 'traditional',
                        'Realistic' => 'realistic',
                        'Minimalist' => 'minimalist',
                        'Watercolor' => 'watercolor',
                        'Tribal' => 'tribal',
                        'Geometric' => 'geometric',
                        'Japanese' => 'japanese',
                        'Other' => 'other',
                    ],
                ],
                'budget_range' => [
                    'type' => CustomFieldType::SELECT,
                    'label' => 'Budget Range',
                    'placeholder' => 'Select your budget',
                    'help_text' => 'This helps us understand your expectations.',
                    'required' => false,
                    'options' => [
                        'Under $100' => 'under-100',
                        '$100 - $300' => '100-300',
                        '$300 - $500' => '300-500',
                        '$500 - $1000' => '500-1000',
                        '$1000+' => '1000-plus',
                    ],
                ],
            ],
        ],
        [
            'title' => 'Experience & Medical Information',
            'description' => 'Important information for your safety',
            'icon' => 'heart',
            'fields' => [
                'previous_tattoos' => [
                    'type' => CustomFieldType::RADIO,
                    'label' => 'Previous Tattoos?',
                    'placeholder' => 'Is this your first tattoo?',
                    'help_text' => 'This helps us provide appropriate guidance.',
                    'required' => false,
                    'options' => [
                        'Yes' => 'yes',
                        'No' => 'no',
                    ],
                ],
                'allergies' => [
                    'type' => CustomFieldType::TEXTAREA,
                    'label' => 'Allergies & Medical Conditions',
                    'placeholder' => 'Any allergies or medical conditions?',
                    'help_text' => 'Important for your safety during the tattoo process.',
                    'required' => true,
                ],
                'current_medications' => [
                    'type' => CustomFieldType::TEXTAREA,
                    'label' => 'Current Medications',
                    'placeholder' => 'List any medications you\'re currently taking',
                    'help_text' => 'This helps ensure your safety.',
                    'required' => false,
                ],
            ],
        ],
        [
            'title' => 'Scheduling Preferences',
            'description' => 'When would you like to get tattooed?',
            'icon' => 'calendar',
            'fields' => [
                'preferred_dates' => [
                    'type' => CustomFieldType::TEXT,
                    'label' => 'Preferred Dates',
                    'placeholder' => 'e.g., "Weekends in March" or "Any time in April"',
                    'help_text' => 'When are you available for appointments?',
                    'required' => false,
                ],
                'additional_notes' => [
                    'type' => CustomFieldType::TEXTAREA,
                    'label' => 'Additional Notes',
                    'placeholder' => 'Any other information you\'d like to share...',
                    'help_text' => 'Feel free to add any other details.',
                    'required' => false,
                ],
            ],
        ],
    ];

    public static function createDefaultForm(ArtistForm $artistForm): void
    {
        foreach (self::DEFAULT_SECTIONS as $index => $sectionData) {
            $section = FormSection::create([
                'artist_form_id' => $artistForm->id,
                'title' => $sectionData['title'],
                'description' => $sectionData['description'],
                'icon' => $sectionData['icon'],
                'sort_order' => $index,
                'is_active' => true,
            ]);

            foreach ($sectionData['fields'] as $fieldKey => $fieldData) {
                $field = CustomField::create([
                    'form_section_id' => $section->id,
                    'field_key' => $fieldKey,
                    'field_type' => $fieldData['type'],
                    'label' => $fieldData['label'],
                    'placeholder' => $fieldData['placeholder'],
                    'help_text' => $fieldData['help_text'],
                    'validation_rules' => $fieldData['validation_rules'] ?? null,
                    'sort_order' => array_search($fieldKey, array_keys($sectionData['fields'])),
                    'is_required' => $fieldData['required'],
                    'is_active' => true,
                    'is_built_in' => true,
                ]);

                // Create options for select/radio fields
                if (isset($fieldData['options']) && in_array($fieldData['type'], [CustomFieldType::SELECT, CustomFieldType::RADIO])) {
                    foreach ($fieldData['options'] as $optionLabel => $optionValue) {
                        $field->options()->create([
                            'label' => $optionLabel,
                            'value' => $optionValue,
                            'sort_order' => array_search($optionValue, array_values($fieldData['options'])),
                        ]);
                    }
                }
            }
        }
    }

    public static function createContactFields(ArtistForm $artistForm): void
    {
        // Contact fields are built-in and don't belong to sections
        // They're handled separately in the form display
    }

    public static function createConsentFields(ArtistForm $artistForm): void
    {
        // Consent fields are built-in and don't belong to sections
        // They're handled separately in the form display
    }
}
