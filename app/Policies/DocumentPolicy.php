<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    public function view(User $user, Document $document): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('agent')) {
            return true;
        }

        // Propriétaire du bien lié
        if ($document->bien && $document->bien->user_id === $user->id) {
            return true;
        }

        // Client de la transaction liée
        if ($document->transaction && $document->transaction->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }
}
