<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
   protected $fillable = [
    'classe_id',
    'module_id',
    'professeur_id',
    'date',
    'heure_debut',
    'heure_fin',
    'type_cours_id',
];



    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // app/Models/Seance.php

    public function typeCours()
    {
        return $this->belongsTo(\App\Models\TypeCours::class, 'type_cours_id');
    }


    public function professeur() // Assurez-vous que cette méthode est présente
    {
        return $this->belongsTo(User::class, 'enseignant_id'); // Assurez-vous que 'enseignant_id' est la clé étrangère correcte
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function seancesReportees()
    {
        return $this->hasMany(SeanceReportee::class);
    }
}
