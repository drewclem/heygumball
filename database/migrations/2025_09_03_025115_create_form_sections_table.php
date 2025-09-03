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
        Schema::create('form_sections', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('artist_form_id');
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->string('icon')->nullable()->index();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('artist_form_id')->references('id')->on('artist_forms')->onDelete('cascade');
            $table->index(['artist_form_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_sections');
    }
};
