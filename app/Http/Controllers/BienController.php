<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBienRequest;
use App\Models\Bien;
use App\Models\BienImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BienSoumis;
use App\Notifications\BienStatutChange;
use App\Models\User;

class BienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bien::latest();

        // Filtrage de visibilité selon le rôle
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->hasRole('admin')) {
                // L'admin voit tout
            } elseif ($user->hasRole('proprietaire')) {
                // Chaque proprio ne voit QUE ses propres biens
                $query->where('user_id', $user->id);
            } elseif ($user->hasRole('agent')) {
                // Un agent voit les biens publics + ceux qui lui sont assignés
                $query->where(function($q) use ($user) {
                    $q->where('statut', 'publié')
                      ->orWhere('agent_id', $user->id);
                });
            } else {
                // Client voit uniquement le publié
                $query->where('statut', 'publié');
            }
        } else {
            // Visiteur voit uniquement le publié
            $query->where('statut', 'publié');
        }

        // Search and Filters
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                  ->orWhere('ville', 'like', '%' . $request->search . '%')
                  ->orWhere('adresse', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('nature') && $request->nature != '') {
            $query->where('nature', $request->nature);
        }

        if ($request->has('prix_min') && $request->prix_min != '') {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->has('prix_max') && $request->prix_max != '') {
            $query->where('prix', '<=', $request->prix_max);
        }

        if ($request->has('surface_min') && $request->surface_min != '') {
            $query->where('surface', '>=', $request->surface_min);
        }

        $biens = $query->with('images')->paginate(12)->withQueryString();
        return view('biens.index', compact('biens'));
    }

    public function create()
    {
        $this->authorize('create', Bien::class);
        return view('biens.create');
    }

    public function store(StoreBienRequest $request)
    {
        $this->authorize('create', Bien::class);

        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        
        if (auth()->user()->hasPermissionTo('biens.validate')) {
            $validated['statut'] = 'publié';
            $validated['agent_id'] = auth()->id();
        } else {
            $validated['statut'] = 'en_attente';
        }

        $bien = Bien::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('biens', 'public');
                BienImage::create([
                    'bien_id' => $bien->id,
                    'path' => $path,
                    'is_main' => $index === 0,
                ]);
            }
        }

        if ($bien->statut === 'en_attente') {
            $admins = User::role('admin')->get();
            Notification::send($admins, new BienSoumis($bien));
        }

        return redirect()->route('biens.show', $bien)
            ->with('success', 'Bien créé avec succès. ' . ($bien->statut === 'en_attente' ? 'Il est en attente de validation.' : ''));
    }

    public function show(Bien $bien)
    {
        $this->authorize('view', $bien);
        
        // Incrémenter le compteur de vues uniquement pour les clients connectés
        $user = auth()->user();
        if ($user && $user->hasRole('client')) {
            $bien->increment('vues');
        }

        $bien->load(['images', 'agent', 'owner']);

        $agents = \App\Models\User::role('agent')->get();
        return view('biens.show', compact('bien', 'agents'));
    }

    public function edit(Bien $bien)
    {
        $this->authorize('update', $bien);
        return view('biens.edit', compact('bien'));
    }

    public function update(StoreBienRequest $request, Bien $bien)
    {
        $this->authorize('update', $bien);
        
        $bien->update($request->validated());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('biens', 'public');
                BienImage::create([
                    'bien_id' => $bien->id,
                    'path' => $path,
                    'is_main' => $bien->images()->where('is_main', true)->count() == 0,
                ]);
            }
        }

        return redirect()->route('biens.show', $bien)
            ->with('success', 'Bien mis à jour avec succès.');
    }

    public function destroy(Bien $bien)
    {
        $this->authorize('delete', $bien);
        $bien->delete();

        return redirect()->route('biens.index')
            ->with('success', 'Bien supprimé avec succès.');
    }

    public function publier(Request $request, Bien $bien)
    {
        $this->authorize('validate', $bien);
        
        $agentId = $request->input('agent_id', auth()->id());
        
        $bien->update([
            'statut' => 'publié',
            'agent_id' => $agentId,
        ]);

        $bien->owner->notify(new BienStatutChange($bien, 'publié'));

        return redirect()->back()->with('success', 'Bien publié avec succès.');
    }

    public function suspendre(Bien $bien)
    {
        $this->authorize('update', $bien);
        
        $newStatut = ($bien->statut === 'suspendu') ? 'publié' : 'suspendu';
        $bien->update(['statut' => $newStatut]);

        $message = ($newStatut === 'suspendu') ? 'Le bien a été suspendu.' : 'Le bien a été remis en ligne.';
        return redirect()->back()->with('success', $message);
    }

    public function rejeter(Bien $bien)
    {
        $this->authorize('validate', $bien);
        
        $bien->update(['statut' => 'brouillon']);

        $bien->owner->notify(new BienStatutChange($bien, 'rejeté'));

        return redirect()->back()->with('success', 'Bien rejeté et remis en brouillon.');
    }

    public function destroyImage(BienImage $image)
    {
        $this->authorize('update', $image->bien);
        
        // Supprimer le fichier physique
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès.');
    }
}
