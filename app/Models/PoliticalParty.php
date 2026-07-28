<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoliticalParty extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nec_political_parties';

    protected $guarded = [];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
