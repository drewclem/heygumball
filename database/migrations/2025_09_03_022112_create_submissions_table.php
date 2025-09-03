<?php

use App\Enums\SubmissionStatusType;
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
        Schema::create('submissions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('artist_id');
            $table->ulid('client_id');
            $table->string('status')->default(SubmissionStatusType::PENDING->value)->index();
            $table->text('artist_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('estimated_duration')->nullable();
            $table->decimal('estimated_price', 8, 2)->nullable();
            $table->timestampTz('submitted_at');
            $table->timestampTz('reviewed_at')->nullable();
            $table->string('client_name')->index();
            $table->string('client_email')->index();
            $table->string('client_phone')->nullable()->index();
            $table->timestampsTz();
            $table->softDeletes();

            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->index(['artist_id', 'status']);
            $table->index(['artist_id', 'submitted_at']);
            $table->index(['client_id', 'submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
