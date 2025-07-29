<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = ['seance_id', 'eleve_id'];



    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'eleve_id');
    }

   public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    public function statut()
    {
        return $this->hasMany(StatutPresence::class);
    }
    public function justificationAbsence()
    {
        return $this->hasOne(JustificationAbsence::class);
    }
}
