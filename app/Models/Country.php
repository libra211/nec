<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'nec_countries';

    protected $guarded = [];

    public function diasporaMissions()
    {
        return $this->hasMany(DiasporaMission::class, 'country_id');
    }

    public function voters()
    {
        return $this->hasMany(Voter::class, 'country_id');
    }
}