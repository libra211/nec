<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class EducationMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nec_education_materials';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function getFileIconAttribute(): string
    {
        $ext = $this->file_type ?: pathinfo($this->file_path, PATHINFO_EXTENSION);
        return match (strtolower($ext)) {
            'pdf'           => 'fa-file-pdf',
            'doc', 'docx'   => 'fa-file-word',
            'xls', 'xlsx'   => 'fa-file-excel',
            'ppt', 'pptx'   => 'fa-file-powerpoint',
            'jpg','jpeg','png','gif','webp' => 'fa-file-image',
            'zip','rar','7z' => 'fa-file-archive',
            'mp4','avi','mov' => 'fa-file-video',
            default         => 'fa-file-alt',
        };
    }

    public function getFileTypeLabelAttribute(): string
    {
        $ext = $this->file_type ?: pathinfo($this->file_path, PATHINFO_EXTENSION);
        $labels = [
            'pdf'  => 'PDF Document',
            'doc'  => 'Word Document',
            'docx' => 'Word Document',
            'xls'  => 'Excel Spreadsheet',
            'xlsx' => 'Excel Spreadsheet',
            'ppt'  => 'PowerPoint',
            'pptx' => 'PowerPoint',
            'jpg'  => 'JPEG Image',
            'jpeg' => 'JPEG Image',
            'png'  => 'PNG Image',
            'gif'  => 'GIF Image',
            'webp' => 'WebP Image',
            'zip'  => 'ZIP Archive',
            'rar'  => 'RAR Archive',
            'mp4'  => 'MP4 Video',
            'avi'  => 'AVI Video',
            'txt'  => 'Text File',
        ];
        return $labels[strtolower($ext)] ?: strtoupper($ext) . ' File';
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes <= 0 && $this->file_path) {
            $relative = preg_replace('#^/storage/#', '', $this->file_path);
            if (Storage::exists($relative)) {
                $bytes = (int) Storage::size($relative);
            }
        }
        if ($bytes <= 0) return 'Download';
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function getDownloadsCountLabelAttribute(): string
    {
        $n = (int) ($this->downloads_count ?? 0);
        if ($n < 1000) return (string) $n;
        if ($n < 1000000) return round($n / 1000, 1) . 'k';
        return round($n / 1000000, 1) . 'M';
    }
}
