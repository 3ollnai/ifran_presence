<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['nom', 'niveau', 'professeur_id'];

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }

    public function assiduites()
    {
        return $this->hasMany(Assiduite::class);
    }
}
