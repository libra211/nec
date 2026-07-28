<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\DownloadStat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiDownloadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'download_id' => 'required|integer|exists:nec_downloads,id',
        ]);

        $download = Download::findOrFail($validated['download_id']);
        $download->increment('downloads_count');

        DownloadStat::create([
            'download_id' => $validated['download_id'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'downloaded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Download tracked.',
        ]);
    }
}
