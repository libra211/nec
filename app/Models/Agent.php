<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use SoftDeletes;

    protected $table = 'nec_agents';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getAreaCodeAttribute(): string
    {
        $name = $this->assigned_state ?: $this->state;
        if (!$name) {
            return 'NAT';
        }

        static $map = null;
        if ($map === null) {
            $map = \Illuminate\Support\Facades\Cache::remember('nec_state_codes', 86400, function () {
                return \Illuminate\Support\Facades\DB::table('nec_states')->pluck('code', 'name')->all();
            });
        }

        return $map[$name] ?? strtoupper(substr(preg_replace('/\s+/', '', $name), 0, 3));
    }

    public function getAgentCodeAttribute(): string
    {
        if (!empty($this->attributes['agent_code'])) {
            return $this->attributes['agent_code'];
        }

        return $this->area_code . '-' . str_pad((string) ($this->id ?: 0), 3, '0', STR_PAD_LEFT);
    }

    public function registeredVoters()
    {
        return $this->hasMany(Voter::class, 'registered_by_user_id')->where('registration_type', 'agent');
    }
}
