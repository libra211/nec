<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiLikeController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'media_id' => 'required|integer|exists:nec_media,id',
        ]);

        $media = Media::findOrFail($validated['media_id']);
        $media->increment('likes_count');

        return response()->json([
            'success' => true,
            'likes_count' => $media->fresh()->likes_count,
        ]);
    }
}
