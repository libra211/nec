<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadStat extends Model
{
    use HasFactory;

    protected $table = 'nec_download_stats';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'downloaded_at' => 'datetime',
        ];
    }

    public function download()
    {
        return $this->belongsTo(Download::class);
    }
}
