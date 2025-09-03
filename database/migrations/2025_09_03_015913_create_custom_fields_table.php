<?php

use App\Enums\CustomFieldType;
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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('artist_id');
            $table->string('field_type')->default(CustomFieldType::TEXT->value)->index();
            $table->string('label')->index();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('validation_rules')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->index(['artist_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
