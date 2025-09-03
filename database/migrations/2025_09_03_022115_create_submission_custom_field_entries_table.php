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
        Schema::create('submission_custom_field_entries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('submission_id');
            $table->ulid('custom_field_id');
            $table->text('value')->index();
            $table->string('file_path')->nullable()->index();
            $table->string('file_original_name')->nullable();
            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreign('custom_field_id')->references('id')->on('custom_fields')->onDelete('cascade');
            $table->unique(['submission_id', 'custom_field_id']);
            $table->index(['custom_field_id', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_custom_field_entries');
    }
};
