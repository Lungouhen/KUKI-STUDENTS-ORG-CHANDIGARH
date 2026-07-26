<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class KsoDeployCommand extends Command
{
    protected $signature = 'kso:deploy {--seed : Run seeders after migration}';
    protected $description = 'Setup and deploy KSO Chandigarh Laravel Project in bulk';

    public function handle(): int
    {
        $this->info('===================================================');
        $this->info('  KSO CHANDIGARH LARAVEL DEPLOYMENT AUTOMATION     ');
        $this->info('===================================================');

        // 1. Optimize clear
        $this->info('1. Clearing application caches...');
        Artisan::call('optimize:clear');
        $this->line(Artisan::output());

        // 2. Storage link
        $this->info('2. Creating storage link...');
        try {
            Artisan::call('storage:link');
            $this->line(Artisan::output());
        } catch (\Exception $e) {
            $this->warn('Storage link already exists or failed: ' . $e->getMessage());
        }

        // 3. Migrate
        $this->info('3. Running database migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());

        // 4. Seed if requested
        if ($this->option('seed')) {
            $this->info('4. Seeding database...');
            Artisan::call('db:seed', ['--force' => true]);
            $this->line(Artisan::output());
        }

        $this->info('===================================================');
        $this->info('  KSO CHANDIGARH PROJECT DEPLOYED SUCCESSFULLY!    ');
        $this->info('===================================================');

        return Command::SUCCESS;
    }
}
