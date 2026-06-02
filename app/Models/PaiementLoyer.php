<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaiementLoyer extends Model
{
    protected $table = 'paiements_loyer';

    protected $fillable = [
        'transaction_id',
        'bien_id',
        'user_id',
        'agent_id',
        'montant_loyer',
        'commission_pourcentage',
        'commission_montant',
        'date_echeance',
        'date_paiement',
        'statut',
        'commentaire',
    ];

    protected $casts = [
        'date_echeance'  => 'date',
        'date_paiement'  => 'date',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function locataire()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Retourne le nombre de jours de retard
     */
    public function getJoursRetardAttribute(): int
    {
        if ($this->statut === 'en_attente' && now()->isAfter($this->date_echeance)) {
            return now()->diffInDays($this->date_echeance);
        }
        return 0;
    }
}
