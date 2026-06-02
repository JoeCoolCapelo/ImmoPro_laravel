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
            $table->boolean('client_signed')->default(false);
            $table->timestamp('client_signed_at')->nullable();
            $table->boolean('owner_signed')->default(false);
            $table->timestamp('owner_signed_at')->nullable();
            $table->string('signature_ip')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['client_signed', 'client_signed_at', 'owner_signed', 'owner_signed_at', 'signature_ip']);
        });
    }
};
