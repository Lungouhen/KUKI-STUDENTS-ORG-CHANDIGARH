<?php

namespace App\Providers;

use App\Services\SmtpConfiguration;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        config(['mail.settings_fallback' => config('mail')]);
        app(SmtpConfiguration::class)->applySavedSettings();
    }
}
