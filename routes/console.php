<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('kso:summary', function () {
    $this->info('KSO Chandigarh NGO Website & Membership System');
})->purpose('Display summary of KSO application');
