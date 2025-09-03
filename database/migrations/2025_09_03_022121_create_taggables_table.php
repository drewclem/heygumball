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
        Schema::create('taggables', function (Blueprint $table) {
            $table->ulid('tag_id');
            $table->ulid('taggable_id');
            $table->string('taggable_type');
            $table->timestampsTz();

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
            $table->index(['taggable_id', 'taggable_type']);
            $table->index(['tag_id', 'taggable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};
