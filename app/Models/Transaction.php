<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Transaction extends Model
{
    use LogsActivity;

    protected $fillable = [
        'bien_id',
        'user_id',
        'agent_id',
        'visite_id',
        'type',
        'montant',
        'statut',
        'commission_pourcentage',
        'commission_montant',
        'date_transaction',
        'date_fin_occupation',
        'commentaire',
        'is_archived',
        'client_signed',
        'client_signed_at',
        'owner_signed',
        'owner_signed_at',
        'agency_signed',
        'agency_signed_at',
        'signature_ip',
        'client_signature_image',
        'owner_signature_image',
        'agency_signature_image',
    ];

    protected $casts = [
        'date_transaction' => 'date',
        'date_fin_occupation' => 'date',
        'client_signed_at' => 'datetime',
        'owner_signed_at' => 'datetime',
        'agency_signed_at' => 'datetime',
        'client_signed' => 'boolean',
        'owner_signed' => 'boolean',
        'agency_signed' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function visite()
    {
        return $this->belongsTo(Visite::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function paiementsLoyer()
    {
        return $this->hasMany(PaiementLoyer::class);
    }

    public function isLocation(): bool
    {
        return $this->type === 'location' || ($this->bien && $this->bien->nature === 'location');
    }
}
