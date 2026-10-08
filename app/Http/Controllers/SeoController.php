<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Page;
use App\Models\Setting;

class SeoController extends Controller
{
    public function robots()
    {
        $indexingEnabled = filter_var(Setting::get('seoIndexingEnabled', true), FILTER_VALIDATE_BOOLEAN);
        $content = "User-agent: *\nAllow: /\n";

        if ($indexingEnabled) {
            $content .= 'Sitemap: '.rtrim(config('app.url'), '/').route('sitemap', [], false)."\n";
        }

        return response($content)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function sitemap()
    {
        $indexingEnabled = filter_var(Setting::get('seoIndexingEnabled', true), FILTER_VALIDATE_BOOLEAN);

        $urls = collect();
        if ($indexingEnabled) {
            $baseUrl = rtrim(config('app.url'), '/');
            $toAbsoluteUrl = fn (string $path) => $baseUrl.'/'.ltrim($path, '/');

            $urls = collect([
                route('home', [], false),
                route('about', [], false),
                route('page.faqs', [], false),
                route('events.index', [], false),
                route('gallery.index', [], false),
                route('donations.index', [], false),
                route('contact.index', [], false),
            ])->map($toAbsoluteUrl);

            $pageUrls = Page::query()
                ->where('is_published', true)
                ->pluck('slug')
                ->map(fn (string $slug) => $toAbsoluteUrl(route('page.show', ['slug' => $slug], false)));

            $eventUrls = Event::query()
                ->where('publication_status', 'published')
                ->pluck('id')
                ->map(fn (int $id) => $toAbsoluteUrl(route('events.show', ['id' => $id], false)));

            $urls = $urls->concat($pageUrls)->concat($eventUrls);
        }

        return response()
            ->view('seo.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
