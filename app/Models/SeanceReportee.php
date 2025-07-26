<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeanceReportee extends Model
{
    protected $table = 'seances_reportees';

    protected $fillable = [
        'seance_id', 'nouvelle_date', 'nouvelle_heure_debut', 'nouvelle_heure_fin'
    ];

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }
}
