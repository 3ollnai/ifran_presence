<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JustificationAbsence extends Model
{
    protected $table = 'justification_absence';

    protected $fillable = ['presence_id', 'motif', 'document'];

    public function presence()
    {
        return $this->belongsTo(Presence::class);
    }
}
