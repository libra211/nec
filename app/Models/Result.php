<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_results';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'declared_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function electionEvent()
    {
        return $this->belongsTo(ElectionEvent::class);
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    public function candidateResults()
    {
        return $this->hasMany(CandidateResult::class);
    }
}
