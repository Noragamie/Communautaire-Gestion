<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];
    protected $hidden   = ['password', 'remember_token'];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relations
    public function profile()   { return $this->hasOne(Profile::class); }
    public function authLogs()  { return $this->hasMany(AuthLog::class); }
    public function actualities(){ return $this->hasMany(Actuality::class); }

    // Helpers rôle
    public function isAdmin()     { return $this->role === 'admin'; }
    public function isOperateur() { return $this->role === 'operateur'; }
    public function isVisiteur()  { return $this->role === 'visiteur'; }
}
