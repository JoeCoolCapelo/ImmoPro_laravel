<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Bien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use App\Notifications\NouvelleTransaction;
use Illuminate\Support\Facades\Notification;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Transaction::class);
        
        $query = Transaction::with(['bien', 'client', 'agent']);

        // Recherche par matricule (formaté ou brut) ou titre
        if ($request->has('search')) {
            $search = $request->get('search');
            $cleanSearch = $search;

            // Si c'est un matricule de transaction #TR-0001
            if (str_starts_with(strtoupper($search), '#TR-')) {
                $cleanSearch = (int) substr($search, 4);
                $query->where('id', $cleanSearch);
            } 
            // Si c'est un matricule de bien #00001
            elseif (str_starts_with($search, '#')) {
                $cleanSearch = (int) substr($search, 1);
                $query->where('bien_id', $cleanSearch);
            }
            else {
                $query->where(function($q) use ($search) {
                    $q->where('bien_id', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%")
                      ->orWhereHas('bien', function($sq) use ($search) {
                          $sq->where('titre', 'like', "%{$search}%");
                      });
                });
            }
        }

        // Filtrage selon le rôle
        if (auth()->user()->hasRole('client')) {
            $query->where('user_id', auth()->id());
        } elseif (auth()->user()->hasRole('proprietaire')) {
            $query->whereHas('bien', function($q) {
                $q->where('user_id', auth()->id());
            });
        } elseif (auth()->user()->hasRole('agent')) {
            $query->where('agent_id', auth()->id());
        }

        // Seul l'admin voit les transactions archivées (sauf si on recherche spécifiquement ?)
        if (!auth()->user()->hasRole('admin')) {
            $query->where('is_archived', false);
        }

        $transactions = $query->latest()->paginate(12)->withQueryString();
        return view('transactions.index', compact('transactions'));
    }

    public function archive(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        
        $transaction->update([
            'is_archived' => !$transaction->is_archived
        ]);

        $status = $transaction->is_archived ? 'archivée' : 'désarchivée';
        return redirect()->back()->with('success', "La transaction a été {$status} avec succès.");
    }

    public function libererLocation(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($transaction->type !== 'location') {
            return redirect()->back()->with('error', 'Cette action n\'est possible que pour les locations.');
        }

        DB::transaction(function () use ($transaction) {
            // Archiver la transaction
            $transaction->update([
                'is_archived' => true,
                'date_fin_occupation' => now(),
                'statut' => 'clôturée'
            ]);

            // Libérer le bien
            $transaction->bien->update([
                'statut' => 'en_attente' // Remettre en ligne
            ]);
        });

        return redirect()->back()->with('success', 'Le bien a été libéré et la location archivée.');
    }

    public function create(Request $request)
    {
        $this->authorize('create', Transaction::class);
        $bien = Bien::findOrFail($request->bien_id);
        $clients = User::role('client')->get();
        $selectedClientId = $request->user_id; // pré-rempli depuis la visite
        $visiteId = $request->visite_id;
        return view('transactions.create', compact('bien', 'clients', 'selectedClientId', 'visiteId'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class);

        $validated = $request->validate([
            'bien_id' => 'required|exists:biens,id',
            'user_id' => 'required|exists:users,id',
            'montant' => 'required|numeric|min:0',
            'date_transaction' => 'required|date',
            'commentaire' => 'nullable|string',
            'visite_id' => 'nullable|exists:visites,id',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,png,docx|max:5120',
        ]);

        $bien = Bien::findOrFail($validated['bien_id']);
        
        $transaction = DB::transaction(function () use ($validated, $bien, $request) {
            $commissionPourcentage = $request->input('commission_pourcentage', 10.00);
            $commissionMontant = ($validated['montant'] * $commissionPourcentage) / 100;

            $transaction = Transaction::create([
                'bien_id' => $validated['bien_id'],
                'user_id' => $validated['user_id'],
                'agent_id' => auth()->id(),
                'visite_id' => $validated['visite_id'] ?? null,
                'type' => $bien->nature,
                'montant' => $validated['montant'],
                'commission_pourcentage' => $commissionPourcentage,
                'commission_montant' => $commissionMontant,
                'date_transaction' => $validated['date_transaction'],
                'commentaire' => $validated['commentaire'],
                'statut' => 'validée',
            ]);

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('documents/transactions', 'public');
                    Document::create([
                        'transaction_id' => $transaction->id,
                        'user_id' => auth()->id(),
                        'titre' => $file->getClientOriginalName(),
                        'path' => $path,
                        'type' => 'transaction_doc',
                    ]);
                }
            }

            $bien->update([
                'statut' => $bien->nature === 'vente' ? 'vendu' : 'loué',
            ]);

            // Mettre à jour le statut de la visite associée
            if ($transaction->visite_id) {
                \App\Models\Visite::where('id', $transaction->visite_id)->update([
                    'statut' => 'finalisée'
                ]);
            }

            return $transaction;
        });

        // Notifier le client
        $transaction->client->notify(new NouvelleTransaction($transaction));

        // Notifier les administrateurs
        $admins = User::role('admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new NouvelleTransaction($transaction));

        // Si c'est une LOCATION → générer le premier paiement de loyer
        if ($bien->nature === 'location') {
            $commission = ($transaction->montant * $transaction->commission_pourcentage) / 100;
            \App\Models\PaiementLoyer::create([
                'transaction_id'         => $transaction->id,
                'bien_id'                => $transaction->bien_id,
                'user_id'                => $transaction->user_id,
                'agent_id'               => $transaction->agent_id,
                'montant_loyer'          => $transaction->montant,
                'commission_pourcentage' => $transaction->commission_pourcentage,
                'commission_montant'     => $commission,
                'date_echeance'          => now()->startOfMonth(),
                'statut'                 => 'en_attente',
            ]);
        }

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'La transaction a été enregistrée avec succès.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        $transaction->load(['bien', 'client', 'agent', 'documents']);
        return view('transactions.show', compact('transaction'));
    }

    public function sign(Request $request, Transaction $transaction)
    {
        $request->validate([
            'signature_data' => 'required|string'
        ]);

        $user = auth()->user();
        
        if ($user->id === $transaction->user_id) { // Client
            if ($transaction->client_signed) {
                return redirect()->back()->with('error', 'Vous avez déjà signé cette transaction.');
            }
            $transaction->update([
                'client_signed' => true,
                'client_signed_at' => now(),
                'signature_ip' => request()->ip(),
                'client_signature_image' => $request->signature_data
            ]);
        } elseif ($transaction->bien && $user->id === $transaction->bien->user_id) { // Propriétaire
            if ($transaction->owner_signed) {
                return redirect()->back()->with('error', 'Vous avez déjà signé cette transaction.');
            }
            $transaction->update([
                'owner_signed' => true,
                'owner_signed_at' => now(),
                'signature_ip' => request()->ip(),
                'owner_signature_image' => $request->signature_data
            ]);
        } elseif ($user->hasRole('admin')) { // Agence / Directeur
            if ($transaction->agency_signed) {
                return redirect()->back()->with('error', 'L\'agence a déjà signé cette transaction.');
            }
            $transaction->update([
                'agency_signed' => true,
                'agency_signed_at' => now(),
                'signature_ip' => request()->ip(),
                'agency_signature_image' => $request->signature_data
            ]);
        } else {
            abort(403);
        }

        return redirect()->back()->with('success', 'Votre accord formel a été enregistré avec succès.');
    }
}
