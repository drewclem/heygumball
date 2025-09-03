<?php

use App\Enums\ArtistRoleType;
use App\Enums\ArtistStatusType;
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
        Schema::create('artists', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('username')->unique()->index();
            $table->string('display_name')->index();
            $table->text('bio')->nullable();
            $table->json('specialties')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->string('role')->default(ArtistRoleType::ARTIST->value)->index();
            $table->string('status')->default(ArtistStatusType::ACTIVE->value)->index();
            $table->boolean('booking_form_open')->default(true);
            $table->timestampTz('auto_close_at')->nullable();
            $table->timestampTz('joined_at')->nullable();
            $table->timestampTz('exited_at')->nullable();
            $table->timestampsTz();
            $table->softDeletes();

            $table->unique(['user_id', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
