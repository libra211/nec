<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingStation extends Model
{
    use HasFactory;

    protected $table = 'nec_polling_stations';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class, 'constituency', 'name');
    }

    public function pollingStaff()
    {
        return $this->hasMany(PollingStaff::class);
    }
}
