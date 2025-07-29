<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Professeur extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function modules()
    {
        return $this->hasMany(Module::class, 'professeur_id');
    }

   public function seances()
    {
        return $this->hasMany(Seance::class, 'professeur_id');
    }
}
