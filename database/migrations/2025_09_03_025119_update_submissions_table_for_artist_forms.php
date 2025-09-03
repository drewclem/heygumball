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
        Schema::table('submissions', function (Blueprint $table) {
            $table->ulid('artist_form_id')->nullable()->after('client_id');
            $table->foreign('artist_form_id')->references('id')->on('artist_forms')->onDelete('cascade');
            $table->index(['artist_form_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['artist_form_id']);
            $table->dropIndex(['artist_form_id', 'status']);
            $table->dropColumn('artist_form_id');
        });
    }
};
