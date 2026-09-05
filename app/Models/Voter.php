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
            'eligibility_date' => 'date',
            'deceased_date' => 'date',
            'deceased_at' => 'datetime',
            'registered_at' => 'date',
            'deleted_at' => 'datetime',
        ];
    }

    public function age(): int
    {
        return $this->dob ? $this->dob->age : 0;
    }

    public function getRegisteredByCodeAttribute(): ?string
    {
        if (!empty($this->attributes['registered_by_code'])) {
            return $this->attributes['registered_by_code'];
        }

        if ($this->registration_type === 'agent' && $this->registered_by_user_id) {
            $agent = \App\Models\Agent::find($this->registered_by_user_id);
            return $agent?->agent_code;
        }

        return null;
    }

    public function ageAtElection(): int
    {
        return $this->dob ? \App\Helpers\NecHelper::age_at($this->dob) : 0;
    }

    public function isEligibleToVote(): bool
    {
        if ($this->isDeceased()) {
            return false;
        }

        return $this->ageAtElection() >= \App\Helpers\NecHelper::voting_age();
    }

    public function eligibilityDate(): ?\Illuminate\Support\Carbon
    {
        return $this->dob ? $this->dob->copy()->addYears(\App\Helpers\NecHelper::voting_age()) : null;
    }

    public function isPreRegistered(): bool
    {
        return $this->dob && $this->dob->age < \App\Helpers\NecHelper::voting_age();
    }

    public function isDeceased(): bool
    {
        return $this->status === 'deceased' || $this->deceased_date !== null;
    }

    public function markAsDeceased(array $attributes = []): bool
    {
        return $this->update(array_merge([
            'status' => 'deceased',
            'deceased_date' => $attributes['deceased_date'] ?? now()->format('Y-m-d'),
            'deceased_at' => $attributes['deceased_at'] ?? now(),
            'deceased_by' => $attributes['deceased_by'] ?? null,
            'death_certificate_ref' => $attributes['death_certificate_ref'] ?? null,
            'updated_at' => now(),
        ], $attributes));
    }

    public function revive(): bool
    {
        return $this->update([
            'status' => 'active',
            'deceased_date' => null,
            'deceased_at' => null,
            'deceased_by' => null,
            'death_certificate_ref' => null,
            'updated_at' => now(),
        ]);
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    public function pollingStation()
    {
        return $this->belongsTo(PollingStation::class, 'polling_station_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function diasporaMission()
    {
        return $this->belongsTo(DiasporaMission::class, 'diaspora_mission_id');
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
