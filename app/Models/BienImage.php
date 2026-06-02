<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BienImage extends Model
{
    protected $fillable = ['bien_id', 'path', 'is_main'];

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }
}
