<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ballot extends Model
{
    use HasFactory;

    protected $table = 'nec_ballots';

    protected $guarded = [];

    protected function casts(): array
    {
        return [];
    }

    public function election()
    {
        return $this->belongsTo(ElectionEvent::class);
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }
}
