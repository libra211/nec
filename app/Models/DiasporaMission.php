<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiasporaMission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nec_diaspora_missions';

    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function voters()
    {
        return $this->hasMany(Voter::class, 'diaspora_mission_id');
    }
}