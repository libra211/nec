<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voter extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nec_voters';

    protected $guarded = [];

    protected $hidden = [
        'national_id',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'registered_at' => 'date',
            'deleted_at' => 'datetime',
        ];
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    public function voterTransfers()
    {
        return $this->hasMany(VoterTransfer::class, 'voter_id', 'voter_id');
    }

    public function account()
    {
        return $this->hasOne(VoterAccount::class, 'voter_id', 'voter_id');
    }
}
