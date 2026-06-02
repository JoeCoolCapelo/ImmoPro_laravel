<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PaiementLoyer;
use Illuminate\Http\Request;
use App\Notifications\NouvelleTransaction;

class PaiementLoyerController extends Controller
{
    /**
     * Liste des paiements d'une location
     */
    public function index(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $paiements = $transaction->paiementsLoyer()->orderByDesc('date_echeance')->get();
        return view('paiements.index', compact('transaction', 'paiements'));
    }

    /**
     * Marquer un paiement comme reçu
     */
    public function marquerPaye(Request $request, PaiementLoyer $paiement)
    {
        $this->authorize('update', $paiement->transaction);

        if ($paiement->statut === 'payé') {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Ce loyer est déjà payé.'], 422);
            }
            return redirect()->back()->with('error', 'Ce loyer est déjà payé.');
        }

        $paiement->update([
            'statut'         => 'payé',
            'date_paiement'  => now()->toDateString(),
            'commentaire'    => $request->commentaire,
        ]);

        // Notifier le locataire que son paiement est bien enregistré
        $paiement->locataire->notify(new \App\Notifications\PaiementLoyerRecu($paiement));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paiement enregistré avec succès.',
                'date_paiement' => $paiement->date_paiement->translatedFormat('d M Y'),
                'commission' => number_format($paiement->commission_montant, 0, ',', ' ')
            ]);
        }

        return redirect()->back()->with('success', 'Paiement enregistré. Commission de ' . number_format($paiement->commission_montant, 0, ',', ' ') . ' FCFA créditée.');
    }

    /**
     * Générer le prochain mois de loyer automatiquement
     */
    public function genererProchainMois(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $dernierPaiement = $transaction->paiementsLoyer()->orderBy('date_echeance', 'desc')->first();
        $nouvelleDate = $dernierPaiement ? $dernierPaiement->date_echeance->addMonth() : now()->startOfMonth();

        PaiementLoyer::create([
            'transaction_id'         => $transaction->id,
            'bien_id'                => $transaction->bien_id,
            'user_id'                => $transaction->user_id,
            'agent_id'               => $transaction->agent_id,
            'montant_loyer'          => $transaction->montant,
            'commission_pourcentage' => $transaction->commission_pourcentage,
            'commission_montant'     => ($transaction->montant * $transaction->commission_pourcentage) / 100,
            'date_echeance'          => $nouvelleDate,
            'statut'                 => 'en_attente',
        ]);

        return redirect()->back()->with('success', 'Nouveau mois généré.');
    }

    public function genererAnnee(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $dernierPaiement = $transaction->paiementsLoyer()->orderBy('date_echeance', 'desc')->first();
        $date = $dernierPaiement ? $dernierPaiement->date_echeance->addMonth() : now()->startOfMonth();

        // On génère 12 mois à partir de la dernière échéance
        for ($i = 0; $i < 12; $i++) {
            PaiementLoyer::create([
                'transaction_id'         => $transaction->id,
                'bien_id'                => $transaction->bien_id,
                'user_id'                => $transaction->user_id,
                'agent_id'               => $transaction->agent_id,
                'montant_loyer'          => $transaction->montant,
                'commission_pourcentage' => $transaction->commission_pourcentage,
                'commission_montant'     => ($transaction->montant * $transaction->commission_pourcentage) / 100,
                'date_echeance'          => $date->copy()->addMonths($i),
                'statut'                 => 'en_attente',
            ]);
        }

        return redirect()->back()->with('success', 'Échéancier annuel généré avec succès.');
    }

    public function encaisserTout(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $paiements = $transaction->paiementsLoyer()->where('statut', '!=', 'payé')->get();

        if ($paiements->isEmpty()) {
            return redirect()->back()->with('info', 'Aucun paiement en attente.');
        }

        foreach ($paiements as $paiement) {
            $paiement->update([
                'statut' => 'payé',
                'date_paiement' => now()->toDateString(),
            ]);
            
            // On peut envoyer une seule notification groupée ou individuelle
            // Pour l'instant on reste sur l'individuelle pour la cohérence des reçus
            $paiement->locataire->notify(new \App\Notifications\PaiementLoyerRecu($paiement));
        }

        return redirect()->back()->with('success', $paiements->count() . ' paiements ont été encaissés avec succès.');
    }
}
