<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObserverApplication extends Model
{
    use HasFactory;

    protected $table = 'nec_observer_applications';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'observer_count' => 'integer',
            'approved_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function batch()
    {
        return $this->belongsTo(ObserverBatch::class, 'batch_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getIsAccreditedAttribute(): bool
    {
        return $this->accreditation_number !== null && $this->status === 'approved' && $this->revoked_at === null;
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->title, $this->first_name, $this->other_names, $this->last_name,
        ])));
    }

    public function getApplicationReferenceAttribute(): string
    {
        return '#OA-' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }
}
