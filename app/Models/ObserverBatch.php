<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObserverBatch extends Model
{
    use HasFactory;

    protected $table = 'nec_observer_batches';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
        ];
    }

    public function applications()
    {
        return $this->hasMany(ObserverApplication::class, 'batch_id');
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}