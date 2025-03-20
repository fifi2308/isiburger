<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Import de Sanctum
use Spatie\Permission\Traits\HasRoles; // Import de Spatie\Permission


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles; // Ajout de HasApiTokens et HasRoles

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];
    public function isAdmin()
    {
        return $this->is_admin; // Supposons que 'is_admin' est un champ booléen dans la base de données
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
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


    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    
}
