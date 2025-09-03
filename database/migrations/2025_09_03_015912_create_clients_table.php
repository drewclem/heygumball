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
        Schema::create('clients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('artist_id');
            $table->string('name')->index();
            $table->string('email')->index();
            $table->string('phone')->nullable()->index();
            $table->date('date_of_birth')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('notes')->nullable()->index();
            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->unique(['artist_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
