<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'transaction_id',
        'bien_id',
        'user_id',
        'titre',
        'path',
        'type',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
