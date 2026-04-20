<?php

namespace App\Jobs;

use App\Models\News;
use App\Services\GNewsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class FetchNewsJob implements ShouldQueue
{
    use Queueable;

    public function handle(GNewsService $gnews): void
    {
        $payload = $gnews->fetchTopHeadlines();
        $articles = $payload['articles'] ?? [];

        foreach ($articles as $article) {
            $sourceUrl = $article['url'] ?? null;

            if (!$sourceUrl) continue;

            $title = trim($article['title'] ?? '');
            if ($title === '') continue;

            News::updateOrCreate(
                ['source_url' => $sourceUrl],
                [
                    'api_source'   => 'gnews',
                    'external_id'  => md5($sourceUrl),
                    'source_name'  => data_get($article, 'source.name'),
                    'title'        => $title,
                    'slug'         => Str::slug(Str::limit($title, 150, '')),
                    'summary'      => $article['description'] ?? null,
                    'image_url'    => $article['image'] ?? null,
                    'published_at' => $article['publishedAt'] ?? null,
                    'fetched_at'   => now(),
                    'is_active'    => true,
                    'raw_payload'  => $article,
                ]
            );
        }
    }
}