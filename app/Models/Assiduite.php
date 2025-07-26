<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assiduite extends Model
{
    protected $table = 'assiduite';

    protected $fillable = ['eleve_id', 'module_id', 'note'];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'eleve_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
