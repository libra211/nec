<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $results = collect();

        if (strlen($query) >= 2) {
            $results = News::where('status', 'published')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('body', 'LIKE', "%{$query}%");
                })
                ->orderByDesc('created_at')
                ->paginate(15)
                ->appends(['q' => $query]);
        }

        return view('search.index', compact('query', 'results'));
    }
}
