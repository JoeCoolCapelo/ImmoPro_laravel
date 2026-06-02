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
        Schema::create('biens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['maison', 'appartement', 'terrain', 'local']);
            $table->string('titre');
            $table->longText('description');
            $table->decimal('surface', 10, 2);
            $table->decimal('prix', 15, 2);
            $table->integer('nb_pieces')->nullable();
            $table->string('adresse');
            $table->string('ville');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('statut', ['brouillon', 'en_attente', 'publié', 'vendu', 'loué'])->default('brouillon');
            $table->enum('nature', ['vente', 'location']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};
