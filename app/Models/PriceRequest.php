<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceRequest extends Model
{
    protected $fillable = [
        'bien_id',
        'old_price',
        'new_price',
        'reason',
        'statut',
    ];

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }
}
