<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\Member;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Counters for the admin sidebar badges. Bound only to the admin layout
        // so public pages never run these queries.
        View::composer('layouts.admin', function ($view) {
            // Guard against running before migrations (e.g. during `migrate`
            // on a fresh database), which would otherwise fatal.
            if (! Schema::hasTable('members')) {
                return;
            }

            $view->with([
                'pendingMemberCount' => Member::where('status', 'Pending')->count(),
                'unreadMessageCount' => Schema::hasTable('contact_messages')
                    ? ContactMessage::where('status', 'Unread')->count()
                    : 0,
            ]);
        });
    }
}
