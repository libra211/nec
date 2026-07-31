<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\DownloadStat;
use App\Models\EducationMaterial;

class DownloadController extends Controller
{
    public function index()
    {
        abort_unless(feature_enabled('public_feature_downloads'), 404);
        $downloads = Download::orderByDesc('created_at')->paginate(24);

        return view('downloads.index', compact('downloads'));
    }

    public function forms()
    {
        $forms = Download::where('category', 'forms')
            ->orderByDesc('created_at')
            ->paginate(24);

        return view('downloads.forms', compact('forms'));
    }

    public function serve(string $type, int $id)
    {
        $download = null;
        $material = null;

        if ($type === 'resource') {
            $material = EducationMaterial::findOrFail($id);
            $material->increment('downloads_count');
            $path = $material->file_path;
        } else {
            $download = Download::findOrFail($id);
            $download->increment('downloads_count');
            $path = $download->file_path;

            try {
                DownloadStat::create([
                    'download_id' => $download->id,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'downloaded_at' => now(),
                ]);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if (! $path) {
            abort(404);
        }

        return redirect()->to($path);
    }
}
