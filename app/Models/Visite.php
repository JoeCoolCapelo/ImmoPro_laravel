<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visite extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'bien_id',
        'user_id',
        'date_visite',
        'statut',
        'commentaire',
        'feedback_agent',
        'interested',
    ];

    protected $casts = [
        'date_visite' => 'datetime',
        'interested' => 'boolean',
    ];

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
