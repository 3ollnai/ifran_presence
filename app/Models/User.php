<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'nom', 'prenom', 'email', 'password', 'photo', 'categorie'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


public function etudiant()
{
    return $this->hasOne(Etudiant::class);
}


    public function professeurs()
    {
        return $this->hasMany(Professeur::class);
    }

    public function coordinateur()
    {
        return $this->hasMany(Coordinateur::class);
    }

    public function parent()
    {
        return $this->hasMany(Parents::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

   public function classes()
{
    return $this->belongsToMany(Classe::class, 'class_user');
}
}
