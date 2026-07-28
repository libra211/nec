<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_comments';

    protected $guarded = [];

    public function post()
    {
        return $this->morphTo();
    }
}
