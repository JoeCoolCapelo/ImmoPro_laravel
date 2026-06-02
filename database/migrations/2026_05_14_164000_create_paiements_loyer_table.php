<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements_loyer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('bien_id')->constrained('biens');
            $table->foreignId('user_id')->constrained('users'); // Le locataire
            $table->foreignId('agent_id')->constrained('users'); // L'agent
            $table->decimal('montant_loyer', 15, 2);         // Loyer mensuel
            $table->decimal('commission_pourcentage', 5, 2);  // % commission agence
            $table->decimal('commission_montant', 15, 2);     // Montant commission calculé
            $table->date('date_echeance');                    // Date d'échéance du paiement
            $table->date('date_paiement')->nullable();        // Date réelle de paiement
            $table->enum('statut', ['en_attente', 'payé', 'en_retard'])->default('en_attente');
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements_loyer');
    }
};
