<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomination extends Model
{
    use HasFactory;

    protected $table = 'nec_nominations';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'submitted_date' => 'date',
        ];
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
