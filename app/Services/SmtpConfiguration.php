<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SmtpConfiguration
{
    public function applySavedSettings(): bool
    {
        $fallback = config('mail.settings_fallback', config('mail'));

        try {
            if (! Schema::hasTable('settings')) {
                return false;
            }

            $host = Setting::get('mail_host');
        } catch (Throwable) {
            return false;
        }
        if (! filled($host)) {
            config([
                'mail.default' => $fallback['default'],
                'mail.mailers.smtp' => $fallback['mailers']['smtp'],
            ]);
            $this->purgeResolvedSmtpMailer();

            return false;
        }

        $encryption = Setting::get('mail_encryption');
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => (int) (Setting::get('mail_port') ?: $fallback['mailers']['smtp']['port']),
            'mail.mailers.smtp.encryption' => match ($encryption) {
                'tls', 'ssl' => $encryption,
                'none' => null,
                default => $fallback['mailers']['smtp']['encryption'],
            },
            'mail.mailers.smtp.username' => Setting::get('mail_username') ?: $fallback['mailers']['smtp']['username'],
            'mail.mailers.smtp.password' => Setting::get('mail_password') ?: $fallback['mailers']['smtp']['password'],
        ]);

        $this->purgeResolvedSmtpMailer();

        return true;
    }

    private function purgeResolvedSmtpMailer(): void
    {
        try {
            $mailManager = app('mail.manager');
            if (method_exists($mailManager, 'purge')) {
                $mailManager->purge('smtp');
            }
        } catch (Throwable) {
            // Purging cached mailers is best-effort; a fresh mailer will be
            // built from the updated configuration on the next resolution.
        }
    }
}
