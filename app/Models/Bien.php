<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Bien extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'agent_id',
        'type',
        'titre',
        'description',
        'surface',
        'prix',
        'nb_pieces',
        'adresse',
        'ville',
        'latitude',
        'longitude',
        'statut',
        'vues',
        'nature',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['titre', 'prix', 'statut', 'agent_id'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function images()
    {
        return $this->hasMany(BienImage::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function visites()
    {
        return $this->hasMany(Visite::class);
    }
}
