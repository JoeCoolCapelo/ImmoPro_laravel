<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'photo_url', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public function favorites()
    {
        return $this->belongsToMany(Bien::class, 'favorites')->withTimestamps();
    }

    public function biens()
    {
        return $this->hasMany(Bien::class, 'user_id');
    }

    public function biensGeres()
    {
        return $this->hasMany(Bien::class, 'agent_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function transactionsAsAgent()
    {
        return $this->hasMany(Transaction::class, 'agent_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
