<?php

namespace App\Http\Controllers;

use App\Models\Download;

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
}
