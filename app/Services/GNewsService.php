<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GNewsService
{
    public function fetchTopHeadlines(): array
    {
        $response = Http::timeout(20)
            ->retry(3, 1000)
            ->acceptJson()
            ->withHeaders([
                'X-Api-Key' => config('services.gnews.api_key'),
            ])
            ->get(config('services.gnews.base_url') . '/top-headlines', [
                'category' => config('services.gnews.category'),
                'country'  => config('services.gnews.country'),
                'lang'     => config('services.gnews.language'),
                'max'      => config('services.gnews.max'),
            ]);

        $response->throw();

        return $response->json();
    }
}