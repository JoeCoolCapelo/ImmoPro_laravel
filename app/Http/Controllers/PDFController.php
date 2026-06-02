<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Transaction;
use App\Models\Visite;
use App\Models\Expense;
use App\Models\PriceRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generateOwnerReportPDF()
    {
        $user = auth()->user();
        
        $biens = Bien::where('user_id', $user->id)->with(['images', 'visites'])->get();
        $transactions = Transaction::whereHas('bien', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'validée')->get();

        $stats = [
            'total_biens' => $biens->count(),
            'total_vues' => $biens->sum('vues'),
            'total_visites' => Visite::whereHas('bien', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'revenus_bruts' => $transactions->sum('montant'),
            'commissions' => $transactions->sum('commission_montant'),
            'depenses' => Expense::whereHas('bien', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->sum('amount'),
        ];

        $stats['revenu_net'] = $stats['revenus_bruts'] - $stats['commissions'] - $stats['depenses'];

        $pdf = Pdf::loadView('pdf.owner_report', compact('user', 'biens', 'stats', 'transactions'));
        return $pdf->download('rapport_performance_' . now()->format('Y_m_d') . '.pdf');
    }

    public function generateBienPDF(Bien $bien)
    {
        $data = [
            'bien' => $bien->load('images', 'owner', 'agent'),
            'date' => date('d/m/Y'),
            'settings' => \App\Models\Setting::all()->pluck('value', 'key'),
        ];

        $pdf = Pdf::loadView('pdf.bien', $data)
            ->setPaper('a4')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => false,
                'isFontSubsettingEnabled' => true
            ]);

        return $pdf->download('ImmoPro_Fiche_' . $bien->id . '.pdf');
    }

    public function generateTransactionPDF(Transaction $transaction)
    {
        $data = [
            'transaction' => $transaction->load(['bien', 'client', 'agent']),
            'date' => date('d/m/Y'),
            'settings' => \App\Models\Setting::all()->pluck('value', 'key'),
        ];

        $view = $transaction->type === 'location' ? 'pdf.quittance' : 'pdf.recu';
        $pdf = Pdf::loadView($view, $data)
            ->setPaper('a4')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => false,
                'isFontSubsettingEnabled' => true
            ]);
        
        return $pdf->download('ImmoPro_' . ucfirst($transaction->type) . '_' . $transaction->id . '.pdf');
    }

    public function generateVisitePDF(\App\Models\Visite $visite)
    {
        $data = [
            'visite' => $visite->load(['bien.agent', 'client']),
            'date' => date('d/m/Y'),
            'settings' => \App\Models\Setting::all()->pluck('value', 'key'),
        ];

        $pdf = Pdf::loadView('pdf.visite', $data)
            ->setPaper('a4')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => false,
                'isFontSubsettingEnabled' => true
            ]);
        
        return $pdf->download('ImmoPro_Visite_' . $visite->id . '.pdf');
    }
    public function generateRentReceiptPDF(\App\Models\PaiementLoyer $paiement)
    {
        $this->authorize('view', $paiement->transaction);

        $data = [
            'paiement' => $paiement->load(['bien', 'locataire', 'agent']),
            'date' => date('d/m/Y'),
            'settings' => \App\Models\Setting::all()->pluck('value', 'key'),
        ];

        $pdf = Pdf::loadView('pdf.loyer_recu', $data)
            ->setPaper('a4')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => false,
                'isFontSubsettingEnabled' => true
            ]);
        
        return $pdf->download('Recu_Loyer_' . $paiement->date_echeance->format('m_Y') . '_' . $paiement->id . '.pdf');
    }

    public function generateFinancialReportPDF()
    {
        $this->authorize('users.manage');

        $transactions = Transaction::with(['bien', 'client', 'agent'])->latest()->get();
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        
        $stats = [
            'total_volume' => Transaction::where('statut', 'validée')->sum('montant'),
            'total_commissions' => Transaction::where('statut', 'validée')->sum('commission_montant')
                                 + \App\Models\PaiementLoyer::where('statut', 'payé')->sum('commission_montant'),
        ];

        $pdf = Pdf::loadView('pdf.financial_report', compact('transactions', 'settings', 'stats'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isFontSubsettingEnabled' => true
            ]);

        return $pdf->download('Rapport_Financier_' . now()->format('Y-m-d') . '.pdf');
    }

    public function generateContractPDF(Transaction $transaction)
    {
        // Require all three signatures to generate a contract
        if (!$transaction->client_signed || !$transaction->owner_signed || !$transaction->agency_signed) {
            abort(403, 'Toutes les parties (Client, Propriétaire et Agence) doivent signer pour générer le contrat formel.');
        }

        $this->authorize('view', $transaction);
        $transaction->load(['bien', 'client', 'agent']);
        
        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        $pdf = Pdf::loadView('pdf.contract', compact('transaction', 'settings'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'isPhpEnabled' => true
            ]);

        $fileName = 'Contrat_' . ucfirst($transaction->type) . '_TR' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($fileName);
    }
}
