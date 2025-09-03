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
        Schema::create('custom_field_options', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('custom_field_id');
            $table->string('label')->index();
            $table->string('value')->index();
            $table->integer('sort_order')->default(0);
            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('custom_field_id')->references('id')->on('custom_fields')->onDelete('cascade');
            $table->index(['custom_field_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_field_options');
    }
};
