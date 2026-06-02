<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Bien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);

        // 1. Performance des Agents (Top Commissions)
        $agentPerformance = User::role('agent')
            ->withSum('transactionsAsAgent as total_commissions', 'commission_montant')
            ->withCount('transactionsAsAgent as sales_count')
            ->orderByDesc('total_commissions')
            ->get();

        // 2. Répartition des Biens par Type
        $propertyDistribution = Bien::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        // 3. Statistiques de Vente vs Location
        $natureStats = Transaction::select('type', DB::raw('sum(montant) as total_volume'), DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        // 4. Volume Mensuel (6 derniers mois)
        $monthlyVolume = Transaction::select(
                DB::raw('DATE_FORMAT(date_transaction, "%Y-%m") as month'),
                DB::raw('sum(montant) as volume'),
                DB::raw('sum(commission_montant) as commissions')
            )
            ->where('date_transaction', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.index', compact(
            'agentPerformance',
            'propertyDistribution',
            'natureStats',
            'monthlyVolume'
        ));
    }
}
