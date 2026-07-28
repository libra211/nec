<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_regions';

    protected $guarded = [];

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
