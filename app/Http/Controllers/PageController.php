<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = CmsPage::where('slug', $slug)
            ->where('status', 'published')
            ->whereNull('deleted_at')
            ->first();

        if (!$page) {
            throw (new ModelNotFoundException)->setModel(CmsPage::class);
        }

        return view('pages.show', [
            'page' => $page,
            'breadcrumb' => $page->title,
        ]);
    }
}