<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class CacheManagerController extends Controller
{
    private const CLEAR_COMMANDS = [
        'application' => 'cache:clear',
        'config' => 'config:clear',
        'routes' => 'route:clear',
        'views' => 'view:clear',
        'all' => 'optimize:clear',
    ];

    public function index()
    {
        return view('admin.cache.index', [
            'cacheDriver' => config('cache.default'),
            'configurationCached' => app()->configurationIsCached(),
            'routesCached' => app()->routesAreCached(),
            'compiledViewCount' => count(glob(storage_path('framework/views/*.php')) ?: []),
        ]);
    }

    public function clear(string $type)
    {
        abort_unless(array_key_exists($type, self::CLEAR_COMMANDS), 404);

        $command = self::CLEAR_COMMANDS[$type];

        try {
            if (Artisan::call($command) !== 0) {
                return back()->with('error', 'The requested cache could not be cleared. Check the application logs.');
            }
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'The requested cache could not be cleared. Check the application logs.');
        }

        AuditLog::log('CLEAR_APPLICATION_CACHE', [
            'cache' => $type,
            'command' => $command,
        ]);

        return back()->with('success', ucfirst($type).' cache cleared successfully.');
    }
}
