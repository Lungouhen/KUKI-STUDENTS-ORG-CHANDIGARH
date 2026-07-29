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

        // 0. Ensure writable runtime directories and the SQLite file exist.
        $this->info('0. Verifying runtime directories...');
        foreach ([
            storage_path('app/public'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ] as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir, 0775, true);
                $this->line("   created {$dir}");
            }
            if (! is_writable($dir)) {
                $this->error("   NOT WRITABLE: {$dir} — run: chmod -R 775 storage bootstrap/cache");

                return Command::FAILURE;
            }
        }

        if (config('database.default') === 'sqlite') {
            $sqlite = config('database.connections.sqlite.database');
            if ($sqlite !== ':memory:' && ! file_exists($sqlite)) {
                touch($sqlite);
                $this->line("   created {$sqlite}");
            }
        }

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
