<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Le contrôleur filtre les résultats selon le rôle
    }

    public function view(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('agent')) {
            return $user->id === $transaction->agent_id;
        }

        if ($user->hasRole('client')) {
            return $user->id === $transaction->user_id;
        }

        if ($user->hasRole('proprietaire') && $transaction->bien) {
            return $user->id === $transaction->bien->user_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('transactions.create');
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('agent') && $user->id === $transaction->agent_id);
    }
}
