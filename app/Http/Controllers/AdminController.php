<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Spatie\Activitylog\Models\Activity;
use App\Models\Bien;
use App\Notifications\BroadcastMessage;
use Illuminate\Support\Facades\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function broadcast()
    {
        $this->authorize('users.manage');
        return view('admin.broadcast');
    }

    public function broadcastStore(Request $request)
    {
        $this->authorize('users.manage');
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required|in:all,agents,proprietaires,clients'
        ]);

        $query = User::query();
        
        if ($request->target !== 'all') {
            $role = substr($request->target, 0, -1); // remove 's'
            if ($request->target === 'proprietaires') $role = 'proprietaire';
            $query->role($role);
        }

        $users = $query->get();

        try {
            Notification::send($users, new BroadcastMessage($request->subject, $request->message));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de l\'envoi du broadcast: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi du message : ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Le message a été envoyé à ' . $users->count() . ' utilisateurs. Les emails seront traités en arrière-plan.');
    }
    public function pendingBiens()
    {
        $this->authorize('users.manage');
        $biens = Bien::where('statut', 'en_attente')->with('owner')->latest()->paginate(20);
        $agents = User::role('agent')->get();
        return view('admin.biens.pending', compact('biens', 'agents'));
    }

    public function bulkValidate(Request $request)
    {
        $this->authorize('users.manage');
        
        $request->validate([
            'bien_ids' => 'required|array',
            'bien_ids.*' => 'exists:biens,id',
            'agent_id' => 'required|exists:users,id',
        ]);

        $bienIds = $request->bien_ids;
        $agentId = $request->agent_id;

        $biens = Bien::whereIn('id', $bienIds)->get();

        foreach ($biens as $bien) {
            $bien->update([
                'statut' => 'publié',
                'agent_id' => $agentId,
            ]);

            $bien->owner->notify(new BienStatutChange($bien, 'publié'));
        }

        return redirect()->back()->with('success', count($bienIds) . ' biens ont été validés et assignés avec succès.');
    }
    public function users(Request $request)
    {
        $this->authorize('users.manage');

        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        /* ─── Export CSV ─── */
        if ($request->get('export') === 'csv') {
            $usersExport = $query->get();
            $columns = ['ID', 'Nom', 'Email', 'Role', 'Date Inscription'];

            return response()->streamDownload(function () use ($usersExport, $columns) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF"); // BOM UTF-8 pour Excel
                fputcsv($file, $columns, ';');
                foreach ($usersExport as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->roles->first()?->name ?? 'Aucun',
                        $user->created_at->format('Y-m-d H:i:s'),
                    ], ';');
                }
                fclose($file);
            }, 'utilisateurs_' . date('Y-m-d') . '.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        /* ─── Export PDF ─── */
        if ($request->get('export') === 'pdf') {
            $usersExport = $query->get();
            $settings = [
                'agency_name'     => Setting::get('agency_name', 'ImmoPro'),
                'agency_director' => Setting::get('agency_director', ''),
                'agency_logo'     => Setting::get('agency_logo'),
                'contact_email'   => Setting::get('contact_email', ''),
                'contact_phone'   => Setting::get('contact_phone', ''),
            ];

            $pdf = Pdf::loadView('pdf.users', [
                'users'        => $usersExport,
                'settings'     => $settings,
                'filterSearch' => $request->search,
                'filterRole'   => $request->role,
            ])->setPaper('a4', 'portrait');

            return $pdf->download('utilisateurs_' . date('Y-m-d') . '.pdf');
        }

        /* ─── Affichage paginé ─── */
        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function userCreate()
    {
        $this->authorize('users.manage');
        // L'admin peut créer des agents, propriétaires ou clients
        // (les propriétaires et clients peuvent s'inscrire eux-mêmes,
        //  seul l'admin peut créer des agents)
        $roles = Role::whereNotIn('name', ['admin'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function userStore(Request $request)
    {
        $this->authorize('users.manage');
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        // Envoyer le message de bienvenue
        try {
            $user->notify(new \App\Notifications\WelcomeNotification());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur d'envoi de mail de bienvenue (admin): " . $e->getMessage());
        }

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }

    public function userEdit(User $user)
    {
        $this->authorize('users.manage');
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $this->authorize('users.manage');
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role'  => ['required', 'exists:roles,name'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function userDestroy(User $user)
    {
        $this->authorize('users.manage');
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function logs(Request $request)
    {
        $this->authorize('users.manage');
        
        $query = Activity::with('causer');

        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        if ($request->filled('event')) {
            $query->where('description', 'like', '%' . $request->event . '%');
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $activities = $query->latest()->paginate(50);
        $users = User::all();
        
        return view('admin.logs.index', compact('activities', 'users'));
    }

    public function exportTransactions()
    {
        $this->authorize('users.manage');
        
        $transactions = \App\Models\Transaction::with(['bien', 'client', 'agent'])->latest()->get();
        
        $filename = "reporting_financier_" . date('Y-m-d_H-i') . ".csv";

        $handle = fopen('php://temp', 'w+');
        
        // BOM UTF-8 pour Excel
        fputs($handle, "\xEF\xBB\xBF");
        
        // Header
        fputcsv($handle, [
            'ID', 
            'Bien', 
            'Nature',
            'Client', 
            'Agent', 
            'Prix de Vente/Loyer', 
            'Commission (%)',
            'Comission Montant',
            'Date Transaction', 
            'Statut'
        ], ';');
        
        foreach ($transactions as $t) {
            fputcsv($handle, [
                '#TR-' . str_pad($t->id, 5, '0', STR_PAD_LEFT),
                $t->bien?->titre ?? 'N/A',
                strtoupper($t->type),
                $t->client?->name ?? 'N/A',
                $t->agent?->name ?? 'N/A',
                $t->montant,
                ($t->commission_pourcentage ?? 0) . '%',
                $t->commission_montant ?? 0,
                $t->date_transaction ? (is_string($t->date_transaction) ? $t->date_transaction : $t->date_transaction->format('d/m/Y')) : 'N/A',
                strtoupper($t->statut)
            ], ';');
        }
        
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function settings()
    {
        $this->authorize('users.manage'); // Only admin
        
        $settings = [
            'agency_name' => Setting::get('agency_name', 'ImmoPro'),
            'contact_email' => Setting::get('contact_email', 'josephbangoura0204@gmail.com'),
            'contact_phone' => Setting::get('contact_phone', '+224 625 99 79 03'),
            'currency' => Setting::get('currency', 'GNF'),
            'agent_commission' => Setting::get('agent_commission', '5'),
            'agency_logo' => Setting::get('agency_logo'),
            'agency_director' => Setting::get('agency_director', 'M. Mohamed SYLLA'),
            
            // Team Members
            'team_member_1_name' => Setting::get('team_member_1_name', 'M. Mohamed SYLLA'),
            'team_member_1_role' => Setting::get('team_member_1_role', 'Directeur Général'),
            'team_member_2_name' => Setting::get('team_member_2_name', 'Mme. Fatou BARRY'),
            'team_member_2_role' => Setting::get('team_member_2_role', 'Responsable Financière'),
            'team_member_3_name' => Setting::get('team_member_3_name', 'M. Camara ISSA'),
            'team_member_3_role' => Setting::get('team_member_3_role', 'Gestionnaire de Patrimoine'),
            'team_member_1_photo' => Setting::get('team_member_1_photo'),
            'team_member_2_photo' => Setting::get('team_member_2_photo'),
            'team_member_3_photo' => Setting::get('team_member_3_photo'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function settingsStore(Request $request)
    {
        $this->authorize('users.manage');
        
        $data = $request->validate([
            'agency_name' => 'required|string|max:255',
            'agency_director' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:30',
            'currency' => 'required|string|max:10',
            'agent_commission' => 'required|numeric|min:0|max:100',
            'agency_logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'team_member_1_name' => 'nullable|string|max:255',
            'team_member_1_role' => 'nullable|string|max:255',
            'team_member_2_name' => 'nullable|string|max:255',
            'team_member_2_role' => 'nullable|string|max:255',
            'team_member_3_name' => 'nullable|string|max:255',
            'team_member_3_role' => 'nullable|string|max:255',
            'team_member_1_photo' => 'nullable|image|max:2048',
            'team_member_2_photo' => 'nullable|image|max:2048',
            'team_member_3_photo' => 'nullable|image|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('agency_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('agency_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('agency_logo')->store('logos', 'public');
            Setting::updateOrCreate(
                ['key' => 'agency_logo'],
                ['value' => $logoPath, 'type' => 'string', 'group' => 'general']
            );
        }

        // Handle team member photos
        for ($i = 1; $i <= 3; $i++) {
            $key = "team_member_{$i}_photo";
            if ($request->hasFile($key)) {
                $oldPhoto = Setting::get($key);
                if ($oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
                $photoPath = $request->file($key)->store('team', 'public');
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $photoPath, 'type' => 'string', 'group' => 'team']
                );
            }
        }

        // Save other settings (exclude agency_logo and team photos from the loop)
        unset($data['agency_logo']);
        for ($i = 1; $i <= 3; $i++) {
            unset($data["team_member_{$i}_photo"]);
        }
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => is_numeric($value) ? 'integer' : 'string', 'group' => 'general']
            );
        }

        return redirect()->route('admin.settings')->with('success', 'Paramètres mis à jour avec succès.');
    }

    public function triggerRentReminders()
    {
        $this->authorize('users.manage'); // admin only
        
        \Illuminate\Support\Facades\Artisan::call('notify:rent-due');
        $output = \Illuminate\Support\Facades\Artisan::output();
        
        return back()->with('success', "Système d'alerte déclenché : " . trim($output));
    }
}
