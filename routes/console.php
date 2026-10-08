<?php

use App\Models\AuditLog;
use App\Models\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('kso:summary', function () {
    $this->info('KSO Chandigarh NGO Website & Membership System');
})->purpose('Display summary of KSO application');

Artisan::command('pages:publish-scheduled', function () {
    $publishedIds = DB::transaction(function () {
        $pages = Page::where('publication_status', 'scheduled')
            ->whereNotNull('scheduled_publish_at')
            ->where('scheduled_publish_at', '<=', now())
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        foreach ($pages as $page) {
            $page->update([
                'publication_status' => 'published',
                'scheduled_publish_at' => null,
                'is_published' => true,
            ]);
        }

        if ($pages->isNotEmpty()) {
            AuditLog::log('PUBLISH_SCHEDULED_PAGES', ['page_ids' => $pages->modelKeys()]);
        }

        return $pages->modelKeys();
    });

    $this->info(count($publishedIds).' scheduled page(s) published.');
})->purpose('Publish CMS pages whose scheduled publication time has arrived');

Schedule::useCache('file');
Schedule::command('pages:publish-scheduled')->everyMinute()->withoutOverlapping();
