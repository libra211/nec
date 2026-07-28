<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'nec_complaints';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'received_date' => 'date',
            'resolved_date' => 'date',
        ];
    }
}
