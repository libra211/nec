<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'nec_tags';

    protected $guarded = [];

    public function news()
    {
        return $this->morphedByMany(News::class, 'taggable');
    }
}
