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
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('agency_signed')->default(false);
            $table->timestamp('agency_signed_at')->nullable();
            $table->longText('agency_signature_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['agency_signed', 'agency_signed_at', 'agency_signature_image']);
        });
    }
};
