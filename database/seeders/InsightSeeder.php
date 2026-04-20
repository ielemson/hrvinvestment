<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CaseStudy;
use App\Models\News;
use Illuminate\Support\Str;

class InsightSeeder extends Seeder
{
    public function run(): void
    {
        // Case Studies (8 items)
        for ($i = 1; $i <= 8; $i++) {
            CaseStudy::create([
                'title' => "Business {$i}",
                'slug' => Str::slug("Business {$i}"),
                'website_url' => "https://example{$i}.com",
                'image' => "assets/imgs/demo/business{$i}.jpg",
                'content' => "This is a sample partnership description for Business {$i}.",
                'is_featured' => $i <= 3 ? 1 : 0,
                'is_active' => 1,
                'sort_order' => $i,
            ]);
        }

        // News (8 items)
        for ($i = 1; $i <= 8; $i++) {
            News::create([
                'api_source' => 'demo',
                'external_id' => "news_{$i}",
                'source_name' => 'BBC News',
                'source_url' => "https://bbc.com/news/sample-{$i}",
                'title' => "Global Economic Update {$i}",
                'slug' => Str::slug("Global Economic Update {$i}"),
                'summary' => "This is a summary of global economic update {$i}.",
                'image_url' => "https://via.placeholder.com/400x250",
                'published_at' => now()->subDays($i),
                'fetched_at' => now(),
                'is_active' => 1,
                'raw_payload' => [],
            ]);
        }
    }
}