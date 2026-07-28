<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateResult extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_candidate_results';

    protected $guarded = [];

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
