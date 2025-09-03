<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            // Drop old foreign key and index
            $table->dropForeign(['artist_id']);
            $table->dropIndex(['artist_id', 'sort_order']);

            // Add new columns
            $table->ulid('form_section_id')->nullable()->after('id');
            $table->string('field_key')->nullable()->after('form_section_id')->index();
            $table->boolean('is_built_in')->default(false)->after('is_active')->index();

            // Drop old column
            $table->dropColumn('artist_id');

            // Add new foreign key and index
            $table->foreign('form_section_id')->references('id')->on('form_sections')->onDelete('cascade');
            $table->index(['form_section_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_fields', function (Blueprint $table) {
            // Drop new foreign key and index
            $table->dropForeign(['form_section_id']);
            $table->dropIndex(['form_section_id', 'sort_order']);

            // Add back old column
            $table->ulid('artist_id')->after('id');
            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->index(['artist_id', 'sort_order']);

            // Drop new columns
            $table->dropColumn(['form_section_id', 'field_key', 'is_built_in']);
        });
    }
};
