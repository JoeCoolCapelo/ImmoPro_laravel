<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visite;
use Illuminate\Auth\Access\Response;

class VisitePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Filtered in controller
    }

    public function view(User $user, Visite $visite): bool
    {
        return $user->id === $visite->user_id || $user->hasPermissionTo('visites.validate');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('visites.request');
    }

    public function update(User $user, Visite $visite): bool
    {
        return $user->id === $visite->user_id || $user->hasPermissionTo('visites.validate');
    }

    public function delete(User $user, Visite $visite): bool
    {
        // Le client peut supprimer sa propre demande si elle est en attente
        if ($user->id === $visite->user_id && $visite->statut === 'en_attente') {
            return true;
        }

        // L'admin ou l'agent peut supprimer (cacher) la visite
        return $user->hasRole('admin') || $user->hasRole('agent');
    }
}
