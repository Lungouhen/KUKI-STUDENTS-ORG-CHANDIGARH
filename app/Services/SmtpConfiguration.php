<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;

class SmtpConfiguration
{
    public function applySavedSettings(): bool
    {
        if (! Schema::hasTable('settings')) {
            return false;
        }

        $host = Setting::get('mail_host');
        if (! filled($host)) {
            return false;
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => (int) (Setting::get('mail_port') ?: config('mail.mailers.smtp.port')),
            'mail.mailers.smtp.encryption' => match (Setting::get('mail_encryption')) {
                'tls', 'ssl' => Setting::get('mail_encryption'),
                'none' => null,
                default => config('mail.mailers.smtp.encryption'),
            },
            'mail.mailers.smtp.username' => Setting::get('mail_username') ?: config('mail.mailers.smtp.username'),
            'mail.mailers.smtp.password' => Setting::get('mail_password') ?: config('mail.mailers.smtp.password'),
        ]);

        app('mail.manager')->purge('smtp');

        return true;
    }
}
