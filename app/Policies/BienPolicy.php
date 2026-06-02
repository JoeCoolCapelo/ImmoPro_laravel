<?php

namespace App\Policies;

use App\Models\Bien;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BienPolicy
{
    public function viewAny(?User $user): bool
    {
        return true; // Everyone can see the list
    }

    public function view(?User $user, Bien $bien): bool
    {
        // Si c'est un propriétaire, il ne voit QUE ses propres biens
        if ($user && $user->hasRole('proprietaire')) {
            return $user->id === $bien->user_id;
        }

        // Pour les autres (visiteurs, clients, agents, admins)
        if ($bien->statut === 'publié') {
            return true;
        }
        
        if (!$user) {
            return false;
        }

        // Agents and admins can see everything
        return $user->hasPermissionTo('biens.validate');
    }

    public function create(User $user): bool
    {
        // L'agent ne peut plus ajouter de bien, seul le propriétaire ou l'admin peut le faire
        if ($user->hasRole('agent')) {
            return false;
        }
        return $user->hasPermissionTo('biens.create');
    }

    public function update(User $user, Bien $bien): bool
    {
        if ($user->hasPermissionTo('biens.update.all')) {
            return true;
        }

        return $user->hasPermissionTo('biens.update.own') && $user->id === $bien->user_id;
    }

    public function delete(User $user, Bien $bien): bool
    {
        // Interdiction formelle de supprimer un bien qui est actuellement loué ou occupé
        if ($bien->statut === 'loué') {
            return false;
        }

        if ($user->hasPermissionTo('biens.delete')) {
            return true;
        }

        return $user->id === $bien->user_id && $bien->statut === 'brouillon';
    }

    public function validate(User $user, Bien $bien): bool
    {
        return $user->hasPermissionTo('biens.validate');
    }
}
