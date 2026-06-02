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
        Schema::table('visites', function (Blueprint $table) {
            if (!Schema::hasColumn('visites', 'feedback_agent')) {
                $table->text('feedback_agent')->nullable()->after('commentaire');
            }
        });

        Schema::table('biens', function (Blueprint $table) {
            if (!Schema::hasColumn('biens', 'vues')) {
                $table->integer('vues')->default(0)->after('statut');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visites', function (Blueprint $table) {
            $table->dropColumn('feedback_agent');
        });

        Schema::table('biens', function (Blueprint $table) {
            $table->dropColumn('vues');
        });
    }
};
