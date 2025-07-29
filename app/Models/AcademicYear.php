<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
  use HasFactory;

  /**
   * Les attributs qui peuvent être assignés en masse.
   *
   * @var array<int, string>
   */
  protected $fillable = [
      'libelle',
      'date_debut',
      'date_fin',
  ];

  /**
   * Récupère les classes associées à l'année académique.
   */
  public function classes()
  {
      return $this->hasMany(Classe::class, 'annee_academique_id');
  }

  /**
   * Récupère les séances associées à l'année académique.
   */
  public function seance()
  {
      return $this->hasManyThrough(
          Seance::class,
          Classe::class,
          'annee_academique_id',
          'class_id'
      );
  }
}

