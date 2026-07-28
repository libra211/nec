<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_faq';

    protected $guarded = [];
}
