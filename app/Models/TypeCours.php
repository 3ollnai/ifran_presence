<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeCours extends Model
{
    protected $table = 'type_cours';

    protected $fillable = ['nom'];

    public function seances()
    {
        return $this->hasMany(Seance::class, 'type_cours_id');
    }
}
