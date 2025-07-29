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
        'annee_academique_id', // Ajout de la clé étrangère
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function typeCours()
    {
        return $this->belongsTo(\App\Models\TypeCours::class, 'type_cours_id');
    }

public function professeur()
{
    return $this->belongsTo(Professeur::class, 'professeur_id')
        ->with('user:id,nom,prenom');
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

    // Nouvelle relation avec la table academic_years
    public function anneeAcademique()
    {
        return $this->belongsTo(\App\Models\AcademicYear::class, 'annee_academique_id');
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'seance_etudiant', 'seance_id', 'etudiant_id');
    }
}
