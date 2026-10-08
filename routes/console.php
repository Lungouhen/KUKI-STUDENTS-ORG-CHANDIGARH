<?php

use App\Models\AuditLog;
use App\Models\Event;
use App\Models\GeneralContent;
use App\Models\News;
use App\Models\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('kso:summary', function () {
    $this->info('KSO Chandigarh NGO Website & Membership System');
})->purpose('Display summary of KSO application');

Artisan::command('cms:publish-scheduled {--pages-only : Publish only scheduled pages for backwards compatibility}', function () {
    $published = DB::transaction(function () {
        $published = [];
        $models = [
            'pages' => Page::class,
            'news' => News::class,
            'events' => Event::class,
            'content' => GeneralContent::class,
        ];

        if ($this->option('pages-only')) {
            $models = ['pages' => Page::class];
        }

        foreach ($models as $type => $modelClass) {
            $items = $modelClass::where('publication_status', 'scheduled')
                ->whereNotNull('scheduled_publish_at')
                ->where('scheduled_publish_at', '<=', now())
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            foreach ($items as $item) {
                $item->update([
                    'publication_status' => 'published',
                    'scheduled_publish_at' => null,
                    ...($modelClass === Page::class || $modelClass === GeneralContent::class
                        ? ['is_published' => true]
                        : []),
                ]);
            }

            $published[$type] = $items->modelKeys();
            if ($items->isNotEmpty()) {
                AuditLog::log('PUBLISH_SCHEDULED_' . strtoupper($type), [
                    $type === 'content' ? 'content_ids' : $type . '_ids' => $items->modelKeys(),
                ]);
            }
        }

        return $published;
    });

    if ($this->option('pages-only')) {
        $this->info(count($published['pages']).' scheduled page(s) published.');
    } else {
        $this->info(sprintf(
            'Published %d page(s), %d news item(s), %d event(s), and %d content item(s).',
            count($published['pages']),
            count($published['news']),
            count($published['events']),
            count($published['content'])
        ));
    }
})->purpose('Publish scheduled CMS pages, news, events, and content when their publication time arrives');

Artisan::command('pages:publish-scheduled', function () {
    return $this->call('cms:publish-scheduled', ['--pages-only' => true]);
})->purpose('Publish scheduled CMS pages (legacy alias)');

Schedule::useCache('file');
Schedule::command('cms:publish-scheduled')->everyMinute()->withoutOverlapping();
