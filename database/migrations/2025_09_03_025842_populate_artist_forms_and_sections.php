<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Artist;
use App\Models\ArtistForm;
use App\Models\FormSection;
use App\Models\CustomField;
use App\Models\Submission;
use App\Services\BuiltInFieldService;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Create artist forms for all existing artists
    $artists = Artist::all();

    foreach ($artists as $artist) {
      // Create the artist form
      $form = ArtistForm::create([
        'artist_id' => $artist->id,
        'title' => "{$artist->display_name}'s Booking Form",
        'welcome_message' => "Thanks for your interest in getting tattooed! Please fill out this form with as much detail as possible.",
        'submission_success_message' => "Thank you! I'll review your submission and get back to you within 48 hours.",
        'is_active' => $artist->booking_form_open ?? true,
      ]);

      // Create default sections with fields
      BuiltInFieldService::createDefaultForm($form);

      // Update existing submissions to reference this form
      Submission::where('artist_id', $artist->id)->update([
        'artist_form_id' => $form->id
      ]);

      // Note: custom_fields table no longer has artist_id, so we'll handle this differently
      // We'll need to check if there are any custom fields that need to be migrated
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Remove all artist forms and sections
    ArtistForm::truncate();
    FormSection::truncate();

    // Reset the foreign key references
    Submission::query()->update(['artist_form_id' => null]);
    CustomField::query()->update(['form_section_id' => null]);
  }
};
