<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payam extends Model
{
    public $timestamps = false;

    protected $table = 'nec_payams';
    protected $fillable = ['name', 'county_id', 'status'];

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function bomas()
    {
        return $this->hasMany(Boma::class);
    }

    public function state()
    {
        return $this->hasOneThrough(State::class, County::class, 'id', 'id', null, 'state_id');
    }
}
