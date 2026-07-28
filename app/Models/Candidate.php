<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nec_candidates';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'nomination_date' => 'date',
        ];
    }

    public function politicalParty()
    {
        return $this->belongsTo(PoliticalParty::class);
    }

    public function candidateResults()
    {
        return $this->hasMany(CandidateResult::class);
    }

    public function nominations()
    {
        return $this->hasMany(Nomination::class);
    }
}
