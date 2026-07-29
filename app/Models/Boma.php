<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boma extends Model
{
    public $timestamps = false;

    protected $table = 'nec_bomas';
    protected $fillable = ['name', 'payam_id', 'status'];

    public function payam()
    {
        return $this->belongsTo(Payam::class);
    }

    public function payams()
    {
        return $this->hasMany(Payam::class, 'boma_id');
    }
}
