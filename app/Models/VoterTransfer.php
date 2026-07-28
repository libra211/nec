<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoterTransfer extends Model
{
    use HasFactory;

    protected $table = 'nec_voter_transfers';

    protected $guarded = [];

    protected function casts(): array
    {
        return [];
    }

    public function voter()
    {
        return $this->belongsTo(Voter::class);
    }
}
