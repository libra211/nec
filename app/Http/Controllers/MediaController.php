<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Gallery;
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

        return view('media.article', compact('article'));
    }

    public function gallery()
    {
        $photos = Gallery::orderByDesc('created_at')->paginate(24);

        return view('media.gallery', compact('photos'));
    }

    public function videos()
    {
        $videos = Media::where('type', 'video')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('media.videos', compact('videos'));
    }

    public function speeches()
    {
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
