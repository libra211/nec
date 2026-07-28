<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class VoterAccount extends Authenticatable
{
    use Notifiable;

    protected $table = 'nec_voter_accounts';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'pin_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'locked_until' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function voter()
    {
        return $this->belongsTo(Voter::class, 'voter_id', 'voter_id');
    }
}
