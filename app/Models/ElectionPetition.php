<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionPetition extends Model
{
    use HasFactory;

    protected $table = 'nec_election_petitions';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'filed_date' => 'date',
            'resolved_date' => 'date',
        ];
    }
}
