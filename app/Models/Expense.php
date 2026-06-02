<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'bien_id',
        'title',
        'amount',
        'date_expense',
        'description',
    ];

    protected $casts = [
        'date_expense' => 'date',
    ];

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }
}
