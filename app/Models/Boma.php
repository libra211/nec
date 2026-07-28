<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boma extends Model
{
    public $timestamps = false;

    protected $table = 'nec_bomas';
    protected $fillable = ['name', 'county_id', 'payam_id', 'latitude', 'longitude', 'status'];

    public function payam()
    {
        return $this->belongsTo(Payam::class);
    }

    public function payams()
    {
        return $this->hasMany(Payam::class, 'boma_id');
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }
}
