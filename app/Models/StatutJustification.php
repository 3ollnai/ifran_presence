<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutJustification extends Model
{
    use HasFactory;

    protected $table = 'statut_justification';

    protected $fillable = [
        'presence_id',
        'statut',
    ];

    public function presence()
    {
        return $this->belongsTo(Presence::class);
    }

    public function seance()
    {
        return $this->hasOneThrough(
            Seance::class,
            Presence::class,
            'id',
            'id',
            'presence_id',
            'seance_id'
        );
    }

    public function statutPresence()
    {
        return $this->hasOneThrough(
            StatutPresence::class,
            Presence::class,
            'id',
            'id',
            'presence_id',
            'statut_presence_id'
        );
    }

    public function justificationAbsence()
    {
        return $this->hasOneThrough(
            JustificationAbsence::class,
            Presence::class,
            'id',
            'id',
            'presence_id',
            'justification_absence_id'
        );
    }
}
