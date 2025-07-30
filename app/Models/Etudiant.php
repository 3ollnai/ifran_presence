<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
       protected $fillable = [
        'user_id',
        'classe_id',
        'matricule',
    ];

    public function presences()
    {
        return $this->hasMany(Presence::class, 'eleve_id');
    }

    public function parents()
    {
        return $this->belongsToMany(Parents::class, 'parent_etudiant', 'etudiant_id', 'parent_id');
    }

    public function assiduites()
    {
        return $this->hasMany(Assiduite::class, 'eleve_id');
    }

    // App\Models\Etudiant.php
public function user()
{
    return $this->belongsTo(User::class);
}
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

}

