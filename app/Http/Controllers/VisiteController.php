<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use App\Models\Bien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VisiteDemandee;
use App\Notifications\VisiteStatutChange;

class VisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Visite::class);
        $user = auth()->user();

        // Si admin, on voit aussi les supprimées
        $query = Visite::with(['bien.agent', 'bien.owner', 'bien.images', 'client'])->latest();
        
        if ($user->hasRole('admin')) {
            $query->withTrashed();
        }

        if ($user->hasRole('client')) {
            $query->where('user_id', $user->id);
        } elseif ($user->hasRole('proprietaire')) {
            // Un propriétaire voit les visites pour ses propres biens
            $query->whereHas('bien', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($user->hasRole('agent')) {
            // Un agent voit les visites pour les biens dont il est responsable
            $query->whereHas('bien', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            });
        }

        $visites = $query->paginate(15);
        return view('visites.index', compact('visites'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Visite::class);
        $bien = Bien::findOrFail($request->bien_id);
        return view('visites.create', compact('bien'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Visite::class);

        $validated = $request->validate([
            'bien_id' => 'required|exists:biens,id',
            'date_visite' => 'required|date|after:now',
            'commentaire' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['statut'] = 'en_attente';

        $visite = Visite::create($validated);

        // Notifier l'agent responsable ou tous les agents
        $agent = $visite->bien->agent;
        if ($agent) {
            $agent->notify(new VisiteDemandee($visite));
        } else {
            $agents = User::role('agent')->get();
            Notification::send($agents, new VisiteDemandee($visite));
        }

        return redirect()->route('visites.index')
            ->with('success', 'Votre demande de visite a été envoyée.');
    }

    public function show(Visite $visite)
    {
        $this->authorize('view', $visite);
        return view('visites.show', compact('visite'));
    }

    public function update(Request $request, Visite $visite)
    {
        $this->authorize('update', $visite);

        $rules = [
            'interested' => 'sometimes|boolean|nullable',
        ];

        // Seul l'agent/admin peut changer le statut et le feedback
        if (auth()->user()->hasPermissionTo('visites.validate')) {
            $rules['statut'] = 'sometimes|in:en_attente,confirmée,effectuée,annulée';
            $rules['feedback_agent'] = 'nullable|string|max:2000';
            $rules['commentaire'] = 'nullable|string';
        }

        $validated = $request->validate($rules);
        \Illuminate\Support\Facades\Log::info('VISITE_UPDATE', ['data' => $request->all()]);



        $visite->update($validated);

        // Notifier le client du changement de statut (si changé)
        if (isset($validated['statut'])) {
            $visite->client->notify(new VisiteStatutChange($visite, $visite->statut));
        }

        // Nouveau : Notifier l'agent et le propriétaire si le client est intéressé
        if ($request->has('interested') && $request->interested == true) {
            $recipients = collect();
            
            if ($visite->bien->agent) {
                $recipients->push($visite->bien->agent);
            }
            
            if ($visite->bien->owner) {
                $recipients->push($visite->bien->owner);
            }

            if ($recipients->isNotEmpty()) {
                Notification::send($recipients->unique('id'), new \App\Notifications\ClientInteresse($visite));
            }

            // Notifier le client lui-même pour confirmation
            $visite->client->notify(new \App\Notifications\ConfirmationInteretClient($visite));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre réponse a été enregistrée.',
                'interested' => $visite->interested
            ]);
        }

        return redirect()->back()
            ->with('success', 'Votre réponse a été enregistrée. L\'agent et le propriétaire ont été notifiés.');
    }

    public function destroy(Visite $visite)
    {
        $this->authorize('delete', $visite);
        $visite->delete();

        return redirect()->route('visites.index')
            ->with('success', 'Demande de visite annulée.');
    }
}
