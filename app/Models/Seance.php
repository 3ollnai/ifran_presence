<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    protected $fillable = [
        'module_id', 'type_cours_id', 'enseignant_id', 'date', 'heure_debut', 'heure_fin', 'salle', 'classe_id'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function typeCours()
    {
        return $this->belongsTo(TypeCours::class, 'type_cours_id');
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

