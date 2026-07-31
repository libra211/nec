<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constituency extends Model
{
    use HasFactory;

    protected $table = 'nec_constituencies';

    protected $guarded = [];

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function pollingStations()
    {
        return $this->hasMany(PollingStation::class, 'constituency', 'name');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'constituency', 'name');
    }
}
