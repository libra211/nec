<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speech extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_speeches';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'speech_date' => 'date',
        ];
    }
}
