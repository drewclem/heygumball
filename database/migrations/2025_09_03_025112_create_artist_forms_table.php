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
        Schema::create('artist_forms', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('artist_id');
            $table->string('title')->index();
            $table->text('welcome_message')->nullable();
            $table->text('submission_success_message')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->unique(['artist_id']); // One form per artist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_forms');
    }
};
