<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BienController;
use App\Http\Controllers\VisiteController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PaiementLoyerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = auth()->user();
    $query = \App\Models\Bien::where('statut', 'publié');

    if ($user && $user->hasRole('proprietaire')) {
        $query = \App\Models\Bien::where('user_id', $user->id);
    }

    $biens = $query->with('images')
        ->latest()
        ->take(9)
        ->get();
    return view('welcome', compact('biens'));
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $stats = [];

    if ($user->hasRole('admin')) {
        $stats = [
            'biens_count' => \App\Models\Bien::count(),
            'biens_publies' => \App\Models\Bien::where('statut', 'publié')->count(),
            'biens_attente' => \App\Models\Bien::where('statut', 'en_attente')->count(),
            'transactions_count' => \App\Models\Transaction::count(),
            'total_ventes' => \App\Models\Transaction::sum('montant'),
            'total_commissions' => \App\Models\Transaction::sum('commission_montant') + \App\Models\PaiementLoyer::where('statut', 'payé')->sum('commission_montant'),
            'visites_attente' => \App\Models\Visite::where('statut', 'en_attente')->count(),
            'agent_leaderboard' => \App\Models\User::role('agent')
                ->withCount('transactionsAsAgent')
                ->withSum('transactionsAsAgent', 'montant')
                ->orderBy('transactions_as_agent_count', 'desc')
                ->take(5)
                ->get(),
        ];
    } elseif ($user->hasRole('agent')) {
        $stats = [
            'biens_count' => \App\Models\Bien::where('agent_id', $user->id)->count(),
            'biens_publies' => \App\Models\Bien::where('agent_id', $user->id)->where('statut', 'publié')->count(),
            'biens_attente' => \App\Models\Bien::where('agent_id', $user->id)->where('statut', 'en_attente')->count(),
            
            // Commissions Vente vs Location
            'commissions_vente' => \App\Models\Transaction::where('agent_id', $user->id)->where('statut', 'validée')->where('type', 'vente')->sum('commission_montant'),
            'commissions_location' => \App\Models\Transaction::where('agent_id', $user->id)->where('statut', 'validée')->where('type', 'location')->sum('commission_montant') 
                                    + \App\Models\PaiementLoyer::where('agent_id', $user->id)->where('statut', 'payé')->sum('commission_montant'),
            
            // Visites (On inclut les supprimées/masquées dans les stats de performance)
            'visites_effectuees' => \App\Models\Visite::withTrashed()->whereHas('bien', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })->where('statut', 'effectuée')->count(),
            'visites_confirmees' => \App\Models\Visite::withTrashed()->whereHas('bien', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })->where('statut', 'confirmée')->count(),
            'visites_annulees' => \App\Models\Visite::withTrashed()->whereHas('bien', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })->where('statut', 'annulée')->count(),
            'visites_attente' => \App\Models\Visite::whereHas('bien', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })->where('statut', 'en_attente')->count(),

            'mes_taches' => \App\Models\Task::where('user_id', $user->id)->where('is_completed', false)->latest()->take(5)->get(),
            'visites_sans_feedback' => \App\Models\Visite::with(['bien', 'client'])->whereHas('bien', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            })->where('statut', 'effectuée')->whereNull('feedback_agent')->latest()->take(3)->get(),
        ];

        // Taux de conversion (Transactions validées / Visites EFFECTUÉES)
        $transactionsCount = \App\Models\Transaction::where('agent_id', $user->id)->where('statut', 'validée')->count();
        $stats['conversion_rate'] = $stats['visites_effectuees'] > 0 ? round(($transactionsCount / $stats['visites_effectuees']) * 100, 1) : 0;
    } elseif ($user->hasRole('proprietaire')) {
        $ownerBiens = \App\Models\Bien::where('user_id', $user->id);
        
        $transactions = \App\Models\Transaction::whereHas('bien', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('statut', 'validée');

        $revenusBruts = $transactions->sum('montant');
        $commissionsAgence = $transactions->sum('commission_montant');
        $totalDepenses = \App\Models\Expense::whereHas('bien', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->sum('amount');

        $stats = [
            'biens_count' => $ownerBiens->count(),
            'biens_publies' => $ownerBiens->where('statut', 'publié')->count(),
            'revenus_totaux' => $revenusBruts,
            'total_depenses' => $totalDepenses,
            'revenu_net' => $revenusBruts - $commissionsAgence - $totalDepenses,
            'mon_patrimoine_estime' => \App\Models\Bien::where('user_id', $user->id)
                ->whereIn('statut', ['publié', 'en_attente'])
                ->sum('prix'),
            'marge_negociation' => \App\Models\Bien::where('user_id', $user->id)
                ->whereIn('statut', ['vendu', 'loué'])
                ->get()
                ->sum(function($bien) {
                    $prixFinal = \App\Models\Transaction::where('bien_id', $bien->id)->where('statut', 'validée')->first()->montant ?? $bien->prix;
                    return $bien->prix - $prixFinal;
                }),
            'visites_recues' => \App\Models\Visite::whereHas('bien', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'prochaines_visites' => \App\Models\Visite::whereHas('bien', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('date_visite', '>', now())->where('statut', '!=', 'annulée')->latest()->take(3)->get(),
            'derniers_feedbacks' => \App\Models\Visite::whereHas('bien', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->whereNotNull('feedback_agent')->latest()->take(3)->get(),
            'mes_documents' => \App\Models\Document::whereHas('transaction', function($q) use ($user) {
                $q->whereHas('bien', function($bq) use ($user) {
                    $bq->where('user_id', $user->id);
                });
            })->latest()->take(5)->get(),
            'mes_demandes_prix' => \App\Models\PriceRequest::whereHas('bien', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->latest()->take(3)->get(),
        ];

        // Trouver l'agent principal (le dernier ayant géré un de ses biens)
        $stats['mon_agent'] = \App\Models\User::whereHas('biensGeres', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();

    } else { // Client
        $stats = [
            'visites_demandees' => \App\Models\Visite::where('user_id', $user->id)->count(),
            'visites_confirmees' => \App\Models\Visite::where('user_id', $user->id)->where('statut', 'confirmée')->count(),
            'favoris_count' => $user->favorites()->count(),
            'transactions_effectuees' => \App\Models\Transaction::where('user_id', $user->id)->count(),
            
            // Nouvelles fonctionnalités
            'mes_locations' => \App\Models\Transaction::where('user_id', $user->id)->where('type', 'location')->with('bien')->get(),
            'mes_achats' => \App\Models\Transaction::where('user_id', $user->id)->where('type', 'vente')->with('bien')->get(),
            'prochaines_echeances' => \App\Models\PaiementLoyer::whereHas('transaction', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('statut', 'en_attente')->where('date_echeance', '>=', now())->orderBy('date_echeance')->take(3)->get(),
            'mes_favoris' => $user->favorites()->with('images')->latest()->take(3)->get(),
            'mon_agent' => \App\Models\Transaction::where('user_id', $user->id)->with('agent')->latest()->first()?->agent,
            'agency_phone' => \App\Models\Setting::where('key', 'agency_phone')->first()?->value ?? '22400000000',
        ];
    }

    // Chart Data (Admin, Agent & Proprietaire)
    if ($user->hasRole('admin') || $user->hasRole('agent') || $user->hasRole('proprietaire')) {
        $months = [];
        $biens_data = []; // Pour proprio: Vues
        $transactions_data = []; // Revenus
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->translatedFormat('M');
            
            if ($user->hasRole('proprietaire')) {
                // Pour le proprio, on montre l'évolution des VUES au lieu du nombre de biens
                $biens_data[] = \App\Models\Bien::where('user_id', $user->id)
                    ->whereMonth('created_at', '<=', $month->month)
                    ->whereYear('created_at', '<=', $month->year)
                    ->sum('vues');

                $transactions_data[] = \App\Models\Transaction::whereHas('bien', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->whereMonth('date_transaction', $month->month)
                    ->whereYear('date_transaction', $month->year)
                    ->sum('montant');
            } else {
                $biensQuery = \App\Models\Bien::whereMonth('created_at', $month->month)->whereYear('created_at', $month->year);
                $transQuery = \App\Models\Transaction::whereMonth('created_at', $month->month)->whereYear('created_at', $month->year);
                
                if ($user->hasRole('agent')) {
                    $biensQuery->where('agent_id', $user->id);
                    $transQuery->where('agent_id', $user->id);
                }
                
                $biens_data[] = $biensQuery->count();
                $transactions_data[] = $transQuery->sum('montant');
            }
        }

        $stats['chart_labels'] = $months;
        $stats['biens_chart'] = $biens_data;
        $stats['transactions_chart'] = $transactions_data;
        if ($user->hasRole('admin')) {
            $stats = array_merge($stats, [
                'total_volume' => \App\Models\Transaction::where('statut', 'validée')->sum('montant'),
                'total_commissions' => \App\Models\Transaction::where('statut', 'validée')->sum('commission_montant')
                                     + \App\Models\PaiementLoyer::where('statut', 'payé')->sum('commission_montant'),
                'total_visites' => \App\Models\Visite::count(),
                'total_users' => \App\Models\User::count(),
                'biens_validation' => \App\Models\Bien::where('statut', 'en_attente')->count(),
                'agent_leaderboard' => \App\Models\User::role('agent')
                    ->withCount(['transactionsAsAgent' => function($q) {
                        $q->where('statut', 'validée');
                    }])
                    ->withSum(['transactionsAsAgent' => function($q) {
                        $q->where('statut', 'validée');
                    }], 'montant')
                    ->orderBy('transactions_as_agent_count', 'desc')
                    ->take(5)
                    ->get(),
            ]);
        }
    }

    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('biens', BienController::class)->except(['index', 'show']);
    Route::patch('/biens/{bien}/publier', [BienController::class, 'publier'])->name('biens.publier');
    Route::patch('/biens/{bien}/rejeter', [BienController::class, 'rejeter'])->name('biens.rejeter');
    Route::patch('/biens/{bien}/suspendre', [BienController::class, 'suspendre'])->name('biens.suspendre');
    Route::delete('/biens/images/{image}', [BienController::class, 'destroyImage'])->name('biens.images.destroy');

    Route::resource('visites', VisiteController::class);
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::patch('/transactions/{transaction}/statut', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
    Route::post('/transactions/{transaction}/liberer', [TransactionController::class, 'libererLocation'])->name('transactions.liberer');
    Route::patch('/transactions/{transaction}/archive', [TransactionController::class, 'archive'])->name('transactions.archive');
    Route::post('/transactions/{transaction}/sign', [\App\Http\Controllers\TransactionController::class, 'sign'])->name('transactions.sign');
    Route::resource('transactions', TransactionController::class);

    // Paiements de loyer
    Route::get('/transactions/{transaction}/loyers', [PaiementLoyerController::class, 'index'])->name('paiements.index');
    Route::post('/paiements/{transaction}/generer', [PaiementLoyerController::class, 'genererProchainMois'])->name('paiements.generer');
    Route::post('/paiements/{transaction}/generer-annee', [PaiementLoyerController::class, 'genererAnnee'])->name('paiements.generer-annee');
    Route::post('/paiements/{transaction}/encaisser-tout', [PaiementLoyerController::class, 'encaisserTout'])->name('paiements.encaisser-tout');
    Route::patch('/paiements/{paiement}/payer', [PaiementLoyerController::class, 'marquerPaye'])->name('paiements.payer');

    // Documents
    Route::get('/documents', [\App\Http\Controllers\DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [\App\Http\Controllers\DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [\App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [\App\Http\Controllers\DocumentController::class, 'destroy'])->name('documents.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Tasks
    Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}/toggle', [\App\Http\Controllers\TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::delete('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');

    // Price Requests
    Route::post('/price-requests', [\App\Http\Controllers\PriceRequestController::class, 'store'])->name('price-requests.store');

    // PDF Generation
    Route::get('/biens/{bien}/pdf', [PDFController::class, 'generateBienPDF'])->name('biens.pdf');
    Route::get('/owner/report/pdf', [PDFController::class, 'generateOwnerReportPDF'])->name('biens.owner-report');
    Route::get('/transactions/{transaction}/pdf', [PDFController::class, 'generateTransactionPDF'])->name('transactions.pdf');
    Route::get('/transactions/{transaction}/contract-pdf', [PDFController::class, 'generateContractPDF'])->name('transactions.contract.pdf');
    Route::get('/visites/{visite}/pdf', [PDFController::class, 'generateVisitePDF'])->name('visites.pdf');
    Route::get('/paiements/{paiement}/pdf', [PDFController::class, 'generateRentReceiptPDF'])->name('paiements.pdf');

    // Favorites
    Route::get('/favoris', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/biens/{bien}/favorite', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Reports
    Route::get('/rapports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    // Admin Routes
    Route::middleware(['can:users.manage'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [\App\Http\Controllers\AdminController::class, 'userCreate'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\AdminController::class, 'userStore'])->name('users.store');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\AdminController::class, 'userEdit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userDestroy'])->name('users.destroy');
        Route::get('/logs', [\App\Http\Controllers\AdminController::class, 'logs'])->name('logs');
        Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [\App\Http\Controllers\AdminController::class, 'settingsStore'])->name('settings.store');
        
        // Bulk Validation
        Route::get('/biens/pending', [\App\Http\Controllers\AdminController::class, 'pendingBiens'])->name('biens.pending');
        Route::post('/biens/bulk-validate', [\App\Http\Controllers\AdminController::class, 'bulkValidate'])->name('biens.bulk-validate');

        // Exports
        Route::get('/transactions/export', [\App\Http\Controllers\PDFController::class, 'generateFinancialReportPDF'])->name('transactions.export');

        // Maintenance & Outils
        Route::get('/trigger-rent-reminders', [\App\Http\Controllers\AdminController::class, 'triggerRentReminders'])->name('admin.trigger-rent-reminders');

        // Broadcast
        Route::get('/broadcast', [\App\Http\Controllers\AdminController::class, 'broadcast'])->name('broadcast');
        Route::post('/broadcast', [\App\Http\Controllers\AdminController::class, 'broadcastStore'])->name('broadcast.store');
    });

    // CRM Pipeline
    Route::get('/pipeline', [\App\Http\Controllers\CRMController::class, 'index'])->name('crm.index');

    // Entretien & Dépenses
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class)->only(['index', 'store', 'destroy']);
});

Route::get('/biens', [BienController::class, 'index'])->name('biens.index');
Route::get('/biens/{bien}', [BienController::class, 'show'])->name('biens.show');

require __DIR__.'/auth.php';
