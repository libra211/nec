<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingStaff extends Model
{
    use HasFactory;

    protected $table = 'nec_polling_staff';

    protected $guarded = [];

    public function pollingStation()
    {
        return $this->belongsTo(PollingStation::class);
    }
}
