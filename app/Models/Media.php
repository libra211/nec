<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_media';

    protected $guarded = [];
}
