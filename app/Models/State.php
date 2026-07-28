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

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function counties()
    {
        return $this->hasMany(County::class);
    }
}
