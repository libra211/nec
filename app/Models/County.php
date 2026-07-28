<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_counties';

    protected $guarded = [];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function constituencies()
    {
        return $this->hasMany(Constituency::class);
    }
}
