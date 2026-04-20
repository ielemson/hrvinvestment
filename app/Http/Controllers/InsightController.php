<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseStudy;
use App\Models\News;
use Illuminate\View\View;

class InsightController extends Controller
{
    public function index(): View
    {
        $caseStudies = CaseStudy::query()
            ->where('is_active', 1)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest('id')
            ->get([
                'id',
                'title',
                'slug',
                'website_url',
                'image',
                'content',
                'is_featured',
                'is_active',
                'sort_order',
                'created_at',
            ]);

        $news = News::query()
            ->where('is_active', 1)
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(9, [
                'id',
                'title',
                'slug',
                'source_name',
                'source_url',
                'image_url',
                'summary',
                'published_at',
                'is_active',
                'created_at',
            ]);

        return view('pages.insights', compact('caseStudies', 'news'));
    }
}
