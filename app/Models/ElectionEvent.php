<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionEvent extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_election_events';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'registration_start_date' => 'date',
            'registration_end_date' => 'date',
        ];
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function ballots()
    {
        return $this->hasMany(Ballot::class);
    }
}
