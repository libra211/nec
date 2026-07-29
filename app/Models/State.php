<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_states';

    protected $guarded = [];

    public function scopeStates($query)
    {
        return $query->where('type', 'state');
    }

    public function scopeAdminAreas($query)
    {
        return $query->where('type', 'admin_area');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function counties()
    {
        return $this->hasMany(County::class);
    }

    public function constituencies()
    {
        return $this->hasManyThrough(Constituency::class, County::class, 'state_id', 'county_id');
    }

    public function pollingStations()
    {
        return $this->hasMany(PollingStation::class, 'state', 'name');
    }

    public function payams()
    {
        return $this->hasManyThrough(Payam::class, County::class, 'state_id', 'county_id');
    }

    public function bomas()
    {
        return $this->hasManyThrough(Boma::class, Payam::class, 'county_id', 'payam_id');
    }
}
