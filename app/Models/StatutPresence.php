<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutPresence extends Model
{
    protected $table = 'statut_presences'; 
    protected $fillable = ['presence_id', 'statut'];
    public function presence()
{
    return $this->belongsTo(Presence::class);
}

}
