<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Gallery;
use App\Models\GalleryAlbum;
use App\Models\Media;
use App\Models\News;
use App\Models\Speech;

class MediaController extends Controller
{
    public function news()
    {
        $articles = News::where('status', 'published')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('media.news', compact('articles'));
    }

    public function article($slug)
    {
        $article = News::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $article->increment('views');

        return view('media.article', compact('article'));
    }

    public function gallery()
    {
        abort_unless(feature_enabled('public_feature_gallery'), 404);
        $albums = GalleryAlbum::with(['images' => function ($q) {
            $q->orderBy('sort_order');
        }])->where('status', 'published')
          ->orderByDesc('created_at')
          ->paginate(12);

        return view('media.gallery', compact('albums'));
    }

    public function videos()
    {
        abort_unless(feature_enabled('public_feature_videos'), 404);
        $videos = Media::where('type', 'video')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('media.videos', compact('videos'));
    }

    public function speeches()
    {
        abort_unless(feature_enabled('public_feature_speeches'), 404);
        $speeches = Speech::orderByDesc('speech_date')->paginate(12);

        return view('media.speeches', compact('speeches'));
    }

    public function pressReleases()
    {
        $releases = News::where('category', 'press-release')
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('media.press-releases', compact('releases'));
    }

    public function publications()
    {
        $publications = Download::where('category', 'publications')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('media.publications', compact('publications'));
    }
}
