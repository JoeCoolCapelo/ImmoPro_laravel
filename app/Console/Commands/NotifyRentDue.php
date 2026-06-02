<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaiementLoyer;
use App\Notifications\RentReminderNotification;
use Illuminate\Support\Facades\Notification;

class NotifyRentDue extends Command
{
    protected $signature = 'notify:rent-due';
    protected $description = 'Envoie des rappels pour les loyers arrivant à échéance dans 3 jours';

    public function handle()
    {
        $targetDate = now()->addDays(3)->toDateString();
        
        $paiements = PaiementLoyer::where('statut', 'en_attente')
            ->whereDate('date_echeance', $targetDate)
            ->with('transaction.client')
            ->get();

        foreach ($paiements as $paiement) {
            $client = $paiement->transaction->client;
            if ($client) {
                // Ici on pourrait envoyer un SMS ou Email
                // Pour l'exemple on utilise le système de notification Laravel
                $client->notify(new \App\Notifications\GenericNotification(
                    "Rappel de Loyer",
                    "Votre loyer pour " . $paiement->date_echeance->translatedFormat('F Y') . " arrive à échéance le " . $paiement->date_echeance->format('d/m/Y') . ". Montant : " . number_format($paiement->montant_loyer, 0, ',', ' ') . " GNF.",
                    route('dashboard')
                ));
            }
        }

        $this->info(count($paiements) . " notifications de rappel envoyées.");
    }
}
