<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commissioner extends Model
{
    use HasFactory;

    protected $table = 'nec_commissioners';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'appointed_date' => 'date',
            'date_of_birth'  => 'date',
            'featured'       => 'boolean',
        ];
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset($this->photo);
        }
        return '';
    }

    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', $this->name);
        $initials = '';
        foreach ($parts as $part) {
            if (strlen($part) > 2 || in_array(strtolower($part), ['hon.', 'dr.', 'prof.'])) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }
        return substr($initials, 0, 2) ?: 'NE';
    }
}
